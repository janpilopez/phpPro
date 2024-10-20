<?php
// Splendid Isolation: Dependency Injection
// En la sección anterior, utilicé un indicador y una declaración condicional dentro de una fábrica para
// determinar cuál de las dos clases de CommsManager se debe proporcionar. La solución no fue tan
// flexible como podría haber sido. Las clases que se ofrecían estaban codificadas de forma rígida dentro de un único
// localizador, con la opción de dos componentes integrados en un condicional. Sin embargo, esa inflexibilidad era una
// faceta de mi código de demostración, en lugar de un problema con Service Locator, en sí. Podría haber utilizado cualquier 
// cantidad de estrategias para localizar, crear instancias y devolver objetos
// en nombre del código del cliente. Sin embargo, la verdadera razón por la que Service Locator suele ser tratado con sospecha
//  es el hecho de que un componente debe invocar explícitamente el localizador. Esto parece un poco, bueno, global. 
//  Y los desarrolladores orientados a objetos desconfían con razón de todo lo global.

// El problema
// Siempre que se utiliza el operador new, se elimina la posibilidad de polimorfismo
// dentro de ese ámbito. Imagine un método que implementa un objeto BloggsApptEncoder
// codificado de forma fija, por ejemplo:
class AppointmentMaker
{
    public function makeAppointment(): string
    {
        $encoder = new BloggsApptEncoder();
        return $encoder->encode();
    }
}
// Esto podría funcionar para nuestras necesidades iniciales, pero no permitirá que se active ninguna otra implementación 
// de ApptEncoder
// // en tiempo de ejecución. Eso limita las formas en que se puede usar la clase y hace que sea más difícil probarla.
// Nota Las pruebas unitarias suelen estar diseñadas para centrarse en clases y métodos específicos de forma aislada de un sistema más amplio. Si la clase que se está probando incluye un objeto instanciado directamente, se puede ejecutar todo tipo de código ajeno a la prueba, lo que puede provocar errores y efectos secundarios inesperados. Si, por otro lado, una clase que se está probando adquiere objetos con los que trabaja de alguna manera distinta a la instanciación directa, se le pueden proporcionar objetos falsos (mock o stub) para fines de prueba. Trato los detalles de las pruebas en el Capítulo 18.
// Las instanciaciones directas hacen que el código sea difícil de probar. Gran parte de este capítulo aborda precisamente este tipo de inflexibilidad. Pero, como señalé en la sección anterior, he pasado por alto el hecho de que, incluso si usamos los patrones Prototype o Abstract Factory,
// la instanciación tiene que ocurrir en algún lugar. Aquí nuevamente hay un fragmento de código que crea un objeto Prototype:
// $factory = new TerrainFactory(
//     new EarthSea(),
//     new EarthPlains(),
//     new EarthForest()
//     );
// La clase Prototype TerrainFactory que se llama aquí es un paso en la dirección correcta:
// exige tipos genéricos: Sea, Plains y Forest. La clase deja que el código del cliente
// determine qué implementaciones se deben proporcionar. Pero, ¿cómo se hace esto?

####### // Implementación
// Gran parte de nuestro código llama a las fábricas. Como hemos visto, este modelo se conoce como el patrón
// Service Locator. Un método delega la responsabilidad a un proveedor en el que confía para
// encontrar y proporcionar una instancia del tipo deseado. El ejemplo Prototype invierte esto; simplemente espera que el código de instanciación proporcione implementaciones en el momento de la llamada. No hay
// magia aquí; es simplemente una cuestión de requerir tipos en la firma de un constructor, en lugar de
// crearlos directamente dentro del método. Una variación de esto es proporcionar métodos de establecimiento, de modo que los clientes puedan pasar objetos antes de invocar un método que los use.
// Así que arreglemos AppointmentMaker de esta manera:
class AppointmentMaker2
{
    public function __construct(private ApptEncoder $encoder) {}
    public function makeAppointment(): string
    {
        return $this->encoder->encode();
    }
}
// AppointmentMaker2 ha cedido el control: ya no crea el
// BloggsApptEncoder y hemos ganado flexibilidad. Pero, ¿qué pasa con la lógica para la creación real de objetos
// ApptEncoder? ¿Dónde se encuentran las temidas nuevas declaraciones?
// Necesitamos un componente ensamblador que se encargue de esta tarea. Una estrategia común aquí utiliza un
// archivo de configuración para determinar qué implementaciones deben instanciarse. Hay
// herramientas que nos ayudan con esto, pero este libro trata de hacerlo nosotros mismos, así que construyamos
// una implementación muy simple. Comenzaré con un formato XML rudimentario que describe las relaciones entre las clases abstractas y sus implementaciones preferidas.

// <objects>
// <class name="popp\ch09\batch06\ApptEncoder">
// <instance inst="popp\ch09\batch06\BloggsApptEncoder" />
// </class>
// </objects>

// Esto indica que cuando solicitamos un ApptEncoder, nuestra herramienta debe generar un
// BloggsApptEncoder. Por supuesto, tenemos que crear el ensamblador.
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
            $name = (string)$class['name'];
            $resolvedname = $name;
            if (isset($class->instance)) {
                if (isset($class->instance[0]['inst'])) {
                    $resolvedname = (string)$class->instance[0]['inst'];
                }
            }
            $this->components[$name] = function () use ($resolvedname) {
                $rclass = new \ReflectionClass($resolvedname);
                return $rclass->newInstance();
            };
        }
    }
    public function getComponent(string $class): object
    {
        if (isset($this->components[$class])) {
            $inst = $this->components[$class]();
        } else {
            $rclass = new \ReflectionClass($class);
            $inst = $rclass->newInstance();
        }
        return $inst;
    }
}
$assembler = new ObjectAssembler("src/ch09/batch14_1/objects.xml");
$encoder = $assembler->getComponent(ApptEncoder::class);
$apptmaker = new AppointmentMaker2($encoder);
$out = $apptmaker->makeAppointment();
print $out;
// Debido a que ApptEncoder::class se resuelve en popp\ch09\batch06\ApptEncoder (la clave establecida en el archivo objects.xml), se crea una instancia de un objeto BloggsApptEncoder y se
// devuelve. Puede ver que esto se demuestra en el resultado de este fragmento:
// Datos de citas codificados en formato BloggsCal
// Como ha visto, el código es lo suficientemente inteligente como para crear un objeto concreto incluso si no está en
// el archivo de configuración.
$assembler = new ObjectAssembler("src/ch09/batch14_1/objects.xml");
$encoder = $assembler->getComponent(MegaApptEncoder::class);
$apptmaker = new AppointmentMaker2($encoder);
$out = $apptmaker->makeAppointment();
print $out;
// No hay ninguna clave MegaApptEncoder en el archivo de configuración, pero, como la clase
// MegaApptEncoder existe y se puede crear una instancia, la clase ObjectAssembler puede
// crear y devolver una instancia.
// Pero, ¿qué sucede con los objetos con constructores que requieren argumentos? Podemos lograrlo
// sin mucho más trabajo. ¿Recuerda la clase TerrainFactory más reciente?
// Exige un objeto Sea, uno Plains y uno Forest. Aquí, modifico mi formato XML para
// adaptarse a este requisito.

// <objects>
// <class name="popp\ch09\batch11\TerrainFactory">
// <arg num="0" inst="popp\ch09\batch11\EarthSea" />
// <arg num="1" inst="popp\ch09\batch11\MarsPlains" />
// <arg num="2" inst="popp\ch09\batch11\Forest" />
// </class>
// <class name="popp\ch09\batch11\Forest">
// <instance
// <class name="popp\ch09\batch14\AppointmentMaker2">
// <arg num="0" inst="popp\ch09\batch06\BloggsApptEncoder" />
// </class>
// </objects>

// He descrito dos clases de este capítulo: TerrainFactory y
// AppointmentMaker2. Quiero que TerrainFactory se instancia con un objeto EarthSea,
// un objeto MarsPlains y un objeto EarthForest. También me gustaría que a AppointmentMaker2
// se le pase un objeto BloggsApptEncoder. Como TerrainFactory y
// AppointmentMaker2 ya son clases concretas, no necesito proporcionar elementos <instance>
// en ninguno de los casos.
// Si bien EarthSea y MarsPlains son clases concretas, tenga en cuenta que Forest es abstracto.
// Este es un ejemplo de recursión lógica muy interesante. Aunque Forest no se puede instanciar por sí mismo,
// hay un elemento <class> correspondiente que define una instancia concreta. ¿Cree
// que una nueva versión de ObjectAssembler podrá hacer frente a estos requisitos?
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
            ksort($args);
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
        if (isset($this->components[$class])) {
            $inst = $this->components[$class]();
        } else {
            $rclass = new \ReflectionClass($class);
            $inst = $rclass->newInstance();
        }
        return $inst;
    }
}
// Veamos más de cerca qué hay de nuevo aquí.
// En primer lugar, en el método configure(), ahora recorro todos los elementos <arg> en cada elemento
// <class> y creo una lista de nombres de clases.
// foreach ($class->arg as $arg) {
//     $argclass = (string)$arg['inst'];
//     $args[(int)$arg['num']] = $argclass;
// }
// Luego, en la función de construcción anónima, realmente no tengo que hacer mucho para expandir
// cada uno de estos elementos en instancias de objetos para pasarlos al constructor de mi clase.
// Después de todo, ya he creado el método getComponent() para este propósito.
ksort($args);
$this->components[$name] = function () use ($resolvedname, $args) {
    $expandedargs = [];
    foreach ($args as $arg) {
        $expandedargs[] = $this->getComponent($arg);
    }
    $rclass = new \ReflectionClass($resolvedname);
    return $rclass->newInstanceArgs($expandedargs);
};
// Nota Si estás pensando en crear un ensamblador/contenedor de inyección de dependencias,
// deberías considerar un par de opciones: Pimple (a pesar de su desagradable
// nombre) y Symfony DI. Puedes encontrar más información sobre Pimple en http://pimple.
// sensiolabs.org/; puedes obtener más información sobre el componente Symfony DI
// en http://symfony.com/doc/current/components/dependency_
// injection/introduction.html.

// Ahora podemos mantener la flexibilidad de nuestros componentes y gestionar la instanciación
// de forma dinámica. Probemos la clase ObjectAssembler:
$assembler = new ObjectAssembler("src/ch09/batch14/objects.xml");
$apptmaker = $assembler->getComponent(AppointmentMaker2::class);
$out = $apptmaker->makeAppointment();
print $out;
// Una vez que tenemos un ObjectAssembler, la adquisición de objetos requiere una única declaración.
// La clase AppointmentMaker2 está libre de su dependencia codificada anterior en una instancia
// ApptEncoder. Un desarrollador ahora puede usar el archivo de configuración para controlar qué
// clases se usan en tiempo de ejecución, así como para probar AppointmentMaker2 de manera aislada del
// sistema más amplio.

#########// Inyección de dependencia con atributos
// También podemos utilizar la función de atributos introducida con PHP 8 para trasladar parte de esta
// lógica desde el archivo de configuración a las clases mismas, y podemos hacerlo sin
// sacrificar la funcionalidad que ya hemos definido.
// Aquí hay otro archivo XML. No estoy introduciendo ninguna característica nueva. De hecho, el archivo de configuración se hace cargo de menos lógica.
// <objects>
// <class name="popp\ch09\batch06\ApptEncoder">
// <instance inst="popp\ch09\batch06\BloggsApptEncoder" />
// </class>
// <class name="popp\ch09\batch11\Sea">
// <instance inst="popp\ch09\batch11\EarthSea" />
// </class>
// <class name="popp\ch09\batch11\Plains">
// <instance inst="popp\ch09\batch11\MarsPlains" />
// </class>
// <class name="popp\ch09\batch11\Forest">
// <instance inst="popp\ch09\batch11\EarthForest" />
// </class>
// </objects>

// Quiero generar una nueva versión de TerrainFactory. Si la definición de esto no es evidente en el archivo de configuración,
//  ¿dónde puedo encontrarla? La respuesta se encuentra en la propia clase TerrainFactory: