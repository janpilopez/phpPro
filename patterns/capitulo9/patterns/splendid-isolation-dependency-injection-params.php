<?php

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

class TerrainFactory
{
    #[InjectConstructor(Sea::class, Plains::class, Forest::class)]
    public function __construct(
        private Sea $sea,
        private Plains $plains,
        private Forest $forest
    ) {}
    public function getSea(): Sea
    {
        return clone $this->sea;
    }
    public function getPlains(): Plains
    {
        return clone $this->plains;
    }
    public function getForest(): Forest
    {
        return clone $this->forest;
    }
}
// Esta es simplemente la clase Prototype TerrainConstructor que ya ha visto, pero con
// la adición crucial del atributo InjectConstructor. Esto requiere una definición de clase estándar:
use Attribute;

#[Attribute]

public class InjectConstructor
{
function __construct()
{
}
}

// Entonces, el atributo InjectConstructor define el comportamiento requerido. Quiero que mi
// ejemplo de inyección de dependencia proporcione instancias concretas de las clases abstractas Sea, Plains y
// Forest. Es hora de que la esforzada clase ObjectAssembler dé un paso adelante.
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
        return $inst;
    }
}
// Quizás esto parezca aún más desalentador ahora. Una vez más, sin embargo, no he agregado
// tanto. Vamos a desglosarlo. Todas las adiciones se encuentran en getComponent().
// Si encuentro la clave de clase proporcionada (la variable de argumento $class) en la propiedad de matriz $components,
// simplemente confío en la función anónima correspondiente para encargarse de la instanciación. Si no, entonces la lógica puede encontrarse en los atributos. Para comprobarlo, recorro
// todos los métodos en la clase de destino en busca de un atributo InjectConstructor.
// Si encuentro uno, trato el método relacionado como un constructor. Expando cada uno de los
// argumentos de atributo en una instancia de objeto por derecho propio y luego paso la lista
// terminada a ReflectionClass::newInstanceArgs(). Si, por otro lado, no encuentro el atributo InjectConstructor, simplemente creo una instancia sin argumentos usando ReflectionClass::newInstance().
// Tenga en cuenta que, a lo largo de este ejemplo, creo una matriz denominada $methods que contiene
// los objetos ReflectionMethod para la clase. Esta matriz es redundante aquí, pero
// pronto le encontraremos un uso.
// A continuación, se muestra esa lógica nuevamente, extraída del método ObjectAssembler::getComponent():
$rclass = new \ReflectionClass($class);
$methods = $rclass->getMethods();
$injectconstructor = null;
foreach ($methods as $method) {
    foreach ($method->getAttributes(InjectConstructor::class) as $attribute) {
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

// Tenga en cuenta el uso de la recursión aquí. Para expandir el argumento del atributo a un
// objeto, paso el nombre de la clase a getComponent().
// Ahora, en teoría, puedo generar un objeto TerrainFactory rellenado mágicamente.
$assembler = new ObjectAssembler("src/ch09/batch15/objects.xml");
$terrainfactory = $assembler->getComponent(TerrainFactory::class);
$plains = $terrainfactory->getPlains(); // MarsPlains
// Cuando se llama al objeto ObjectAssembler con el nombre TerrainFactory, el
// método, ObjectAssembler::getcomponent(), primero busca en su matriz $components
// un elemento de configuración coincidente. En este caso, no encuentra ninguno. Entonces recorre
// los métodos en TerrainFactory y se centra en el atributo InjectConstructor.
// Este tiene tres argumentos. Para cada uno de ellos, llama recursivamente a
// getComponent(). En cada uno de estos casos, encuentra un elemento de configuración que
// proporciona una clase desde la cual se puede crear una instancia de un argumento.

// Este código de ejemplo no comprueba la recursión circular. Como mínimo, una
// versión de producción de este código debería evitar que las llamadas recursivas a getComponent()
// se ejecuten en demasiados niveles.

// Por último, completemos el asunto con un nuevo atributo. Inject es similar a
// InjectConstructor excepto que debe aplicarse a métodos estándar. Estos se llamarán
// después de que se cree una instancia del objeto de destino. Aquí está el atributo en uso:
class AppointmentMaker
{
    private ApptEncoder $encoder;
    
    #[Inject(ApptEncoder::class)]
    public function setApptEncoder(ApptEncoder $encoder)
    {
        $this->encoder = $encoder;
    }
    public function makeAppointment(): string
    {
        return $this->encoder->encode();
    }
}

// La directiva aquí es que se debe proporcionar un objeto ApptEncoder a la clase AppointmentMaker después de la instanciación.
// Aquí está la clase Inject estándar que corresponde al atributo:
use Attribute;
#[Attribute]
class Inject
{
    public function __construct()
    {
    }
    }
    // Al igual que con InjectConstructor, en realidad no hace nada útil excepto llenar el espacio de nombres. Es hora de agregar compatibilidad con Inject a ObjectAssembler:
    public function getComponent(string $class): object
    {
    // create $inst -- our object instance
    // and a list of \ReflectionMethod objects
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

// He omitido la mayor parte de getComponent() ya que no cambia aquí. La única
// adición es una llamada a un nuevo método: injectMethods(). Este acepta el nuevo objeto instanciado
// y una matriz de objetos ReflectionMethod. Luego realiza una danza familiar,
// recorriendo todos los métodos con atributos Inject, adquiriendo los argumentos de atributo y
// pasando cada uno de ellos de vuelta a getComponent(). Una vez que se ha compilado una lista de argumentos,
// se invoca el método en la instancia.
// A continuación, se incluye un código de cliente:
$assembler = new ObjectAssembler("src/ch09/batch15/objects.xml");
$apptmaker = $assembler->getComponent(AppointmentMaker::class);
$output = $apptmaker->makeAppointment();
print $output;

// Entonces, cuando llamo a getComponent(), crea una instancia de AppointmentMaker de acuerdo
// con el flujo que hemos explorado. Luego llama a injectMethods() que encuentra un método con
// un atributo Inject en la clase AppointmentMaker. El argumento del atributo especifica
// ApptEncoder. Esta clave de clase se pasa a getComponent() en una llamada recursiva. Debido a que nuestro
// archivo de configuración especifica BloggsApptEncoder como la resolución para ApptEncoder, este
// objeto se instancia y se pasa al método setter.
// Una vez más, esto se demuestra con la salida que son
// datos de Appointment codificados en formato BloggsCal
