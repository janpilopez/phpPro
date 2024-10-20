<?php

// Aquí está todo ObjectAssembler. ¡Comprende una clase de inyección de dependencia de prueba de concepto limitada en no mucho más de 80 líneas!
class ObjectAssembler
{
    private array $components = [];
    public function __construct(string $conf)
    {
        $this->configure($conf);
    }
    private function configure(string $conf): void
    {
        $data = simplexml_load_file($conf);
        foreach ($data->class as $class) {
            $args = [];
            $name = (string)$class['name'];
            $resolvedname = $name;
            foreach ($class->arg as $arg) {
                $argclass = (string)$arg['inst'];
                $args[(int)$arg['num']] = $argclass;
            }
            if (isset($class->instance)) {
                if (isset($class->instance[0]['inst'])) {
                    $resolvedname = (string)$class->instance[0]['inst'];
                }
            }
            $this->components[$name] = function () use (
                $resolvedname,

                $args
            ) {

                $expandedargs = [];
                foreach ($args as $arg) {
                    $expandedargs[] = $this->getComponent($arg);
                }
                $rclass = new \ReflectionClass($resolvedname);
                return $rclass->newInstanceArgs($expandedargs);
            };
        }
    }
    public function getComponent(string $class): object
    {
        // create $inst -- our object instance
        // and a list of \ReflectionMethod objects
        if (isset($this->components[$class])) {
            // instance found in config
            $inst = $this->components[$class]();
            $rclass = new \ReflectionClass($inst::class);
            $methods = $rclass->getMethods();
        } else {
            $rclass = new \ReflectionClass($class);
            $methods = $rclass->getMethods();
            $injectconstructor = null;
            foreach ($methods as $method) {
                foreach (
                    $method->getAttributes(InjectConstructor::class)

                    as $attribute
                ) {

                    $injectconstructor = $attribute;
                    break;
                }
            }
            if (is_null($injectconstructor)) {
                $inst = $rclass->newInstance();
            } else {
                $constructorargs = [];
                foreach ($injectconstructor->getArguments() as $arg) {
                    $constructorargs[] = $this->getComponent($arg);
                }
                $inst = $rclass->newInstanceArgs($constructorargs);
            }
        }
        $this->injectMethods($inst, $methods);
        return $inst;
    }
    public function injectMethods(object $inst, array $methods)
    {
        foreach ($methods as $method) {
            foreach ($method->getAttributes(Inject::class) as $attribute) {
                $args = [];
                foreach ($attribute->getArguments() as $argstring) {
                    $args[] = $this->getComponent($argstring);
                }
                $method->invokeArgs($inst, $args);
            }
        }
    }
}

// Consecuencias
// Ahora hemos visto dos opciones para la creación de objetos. La clase AppConfig era una instancia
// de Service Locator (es decir, una clase con la capacidad de encontrar componentes o servicios en nombre
// de su cliente). El uso de la inyección de dependencias sin duda hace que el código del cliente sea más elegante.
// La clase AppointmentMaker2 no tiene ni idea de las estrategias para la creación de objetos.
// Simplemente hace su trabajo. Por supuesto, esto es lo ideal para una clase. Queremos diseñar clases que puedan centrarse en sus responsabilidades, aisladas lo más posible del sistema más amplio.
// Sin embargo, esta pureza tiene un precio. El componente ensamblador de objetos esconde mucha
// magia. Debemos tratarlo como una caja negra y confiar en que conjure objetos en nuestro nombre.
// Esto está bien, siempre que la magia funcione. El comportamiento inesperado puede ser difícil de depurar.
// El patrón Service Locator, por otro lado, es más simple, aunque integra sus
// componentes en un sistema más amplio. No es el caso de que, si se usa bien, un Service Locator
// haga que las pruebas sean más difíciles. Tampoco hace que un sistema sea inflexible. Un Service Locator puede ser
// configurado para ofrecer componentes arbitrarios para pruebas o según la configuración.
// Pero una llamada codificada a un Service Locator hace que un componente dependa de él.
// Debido a que la llamada se realiza desde dentro del cuerpo de un método, la relación entre
// el cliente y el componente de destino (que es proporcionado por el Service Locator) también
// está un poco oscurecida. Esta relación se hace explícita en el ejemplo de inyección de dependencias porque
// se declara en la firma del método constructor.
// Entonces, ¿qué enfoque deberíamos elegir? Hasta cierto punto, es una cuestión de preferencia.
// Por mi parte, tiendo a preferir comenzar con la solución más simple y luego refactorizar
// a una mayor complejidad, si es necesario. Por esa razón, generalmente opto por Service Locator. Puedo
// crear una clase Registry en unas pocas líneas de código y aumentar su flexibilidad según los
// requisitos. Mis componentes son un poco más inteligentes de lo que me gustaría, pero como
// raramente muevo clases de un sistema a otro, no he sufrido demasiado por el
// efecto de incrustación. Cuando he movido una clase basada en el sistema a una biblioteca independiente,
// no me ha resultado particularmente difícil refactorizar la dependencia Service Locator.
// La inyección de dependencias ofrece pureza, pero requiere otro tipo de incrustación. Debes
// aceptar la magia del ensamblador. Si ya estás trabajando dentro de un marco
// que ofrece esta funcionalidad, no hay razón para no aprovecharla. El componente Symfony
// Dependency Injection, por ejemplo, proporciona una solución híbrida de Service Locator (conocido como el “contenedor de servicios”) e inyección de dependencias. El contenedor de servicios
// administra la instanciación de objetos según la configuración (o el código,
// si lo prefieres) y proporciona una interfaz simple para que los clientes obtengan esos objetos. El
// contenedor de servicios incluso permite el uso de fábricas para la creación de objetos. Por otro lado,
// si está desarrollando sus propios componentes o está utilizando componentes de varios marcos, es posible que desee
// mantener las cosas simples a costa de cierta elegancia.

// Resumen
// En este capítulo se trataron algunos de los trucos que se pueden utilizar para generar objetos. Empecé
// examinando el patrón Singleton, que proporciona acceso global a una única instancia. A continuación,
// observé el patrón Factory Method, que aplica el principio del polimorfismo
// a la generación de objetos. Y combiné Factory Method con el patrón Abstract Factory
// para generar clases creadoras que instancian conjuntos de objetos relacionados. También observé el patrón Prototype y vi cómo la clonación de objetos puede permitir el uso de la composición en
// la generación de objetos. Por último, examiné dos estrategias para la creación de objetos: Service Locator
// e Inyección de dependencias.