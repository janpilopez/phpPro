<!-- Objects and Design -->
<!-- Defining Code Design -->
<!-- Object-Oriented and Procedural Programming -->
<?php
function readParams(string $source): array
{
    $params = [];
    if (preg_match("/\.xml$/i", $source)) {
        // read XML parameters from $source
    } else {
        // read text parameters from $source
    }
    return $params;
}
function writeParams(array $params, string $source): void
{
    if (preg_match("/\.xml$/i", $source)) {
        // write XML parameters to $source
    } else {
        // write text parameters to $source
    }
}

abstract class ParamHandler
{
    protected array $params = [];
    public function __construct(protected string $source) {}
    public function addParam(string $key, string $val): void
    {
        $this->params[$key] = $val;
    }
    public function getAllParams(): array
    {
        return $this->params;
    }
    public static function getInstance(string $filename): ParamHandler
    {
        if (preg_match("/\.xml$/i", $filename)) {
            return new XmlParamHandler($filename);
        }
        return new TextParamHandler($filename);
    }
    abstract public function write(): void;
    abstract public function read(): void;
}

class XmlParamHandler extends ParamHandler
{
 public function write(): void
 {
 // write XML
 // using $this->params
 }
 public function read(): void
 {
 // read XML
 // and populate $this->params
 }
}
// listing 06.06
class TextParamHandler extends ParamHandler
{
 public function write(): void
 {
 // write text
 // using $this->params
 }
 public function read(): void
 {
 // read text
 // and populate $this->params
 }
}
$test = ParamHandler::getInstance(__DIR__ . "/params.xml");
$test->addParam("key1", "val1");
$test->addParam("key2", "val2");
$test->addParam("key3", "val3");
$test->write(); // writing in XML format
// We can also read from either file format:
$test = ParamHandler::getInstance(__DIR__ . "/params.txt");
$test->read(); // reading in text format
$params = $test->getAllParams();
print_r($params);


// Responsabilidad
// El código de control en el ejemplo procedimental asume la responsabilidad de decidir
// sobre el formato, no una, sino dos veces. El código condicional está ordenado en funciones,
// ciertamente, pero esto simplemente disfraza el hecho de un flujo único, que toma decisiones a medida que avanza.
// Las llamadas a readParams() y a writeParams() tienen lugar en diferentes contextos, por lo que estamos
// obligados a repetir la prueba de extensión de archivo en cada función (o a realizar variaciones en esta
// prueba).
// En la versión orientada a objetos, esta elección sobre el formato de archivo se realiza en el método estático
// getInstance(), que prueba la extensión de archivo solo una vez, brindando la subclase
// correcta. El código del cliente no asume ninguna responsabilidad por la implementación. Utiliza el objeto proporcionado sin conocimiento ni interés en la subclase particular a la que pertenece. Solo sabe
// que está trabajando con un objeto ParamHandler y que admitirá write() y
// read(). Mientras que el código procedimental se ocupa de los detalles, el código orientado a objetos
// trabaja únicamente con una interfaz, sin preocuparse por los detalles de la implementación. Como
// la responsabilidad de la implementación recae en los objetos y no en el código del cliente,
// sería fácil cambiar la compatibilidad con nuevos formatos de forma transparente



// Cohesión
// La cohesión es el grado en que los procedimientos próximos están relacionados entre sí. Lo ideal es
// crear componentes que compartan una responsabilidad clara. Si su código distribuye ampliamente
// las rutinas relacionadas, le resultará más difícil mantenerlas, ya que tendrá que buscar
// para hacer cambios.
// Nuestras clases ParamHandler recopilan procedimientos relacionados en un contexto común. Los
// métodos para trabajar con XML comparten un contexto en el que pueden compartir datos y donde
// los cambios en un método pueden reflejarse fácilmente en otro si es necesario (por ejemplo, si necesita
// cambiar el nombre de un elemento XML). Por lo tanto, se puede decir que las clases ParamHandler
// tienen una alta cohesión.
// El ejemplo procedimental, por otro lado, separa los procedimientos relacionados. El código
// para trabajar con XML se distribuye entre funciones.

// Acoplamiento-cupinlg
// El acoplamiento estrecho se produce cuando partes discretas del código de un sistema están estrechamente
// ligadas entre sí, de modo que un cambio en una parte requiere cambios en las otras. El acoplamiento estrecho no es exclusivo del código procedimental, aunque la naturaleza secuencial
// de dicho código lo hace propenso al problema.
// Puede ver este tipo de acoplamiento en el ejemplo procedimental. Las funciones writeParams() y
// readParams() ejecutan la misma prueba en una extensión de archivo para determinar cómo deben
// trabajar con los datos. Cualquier cambio en la lógica que realice en una tendrá que implementarse en la
// otra. Si fuera a agregar un nuevo formato, por ejemplo, tendría que poner las funciones
// en línea entre sí, de modo que ambas implementen una nueva prueba de extensión de archivo de la misma
// manera. Este problema solo puede empeorar a medida que agregue nuevas funciones relacionadas con parámetros.
// El ejemplo orientado a objetos desacopla las subclases individuales entre sí
// y del código del cliente. Si necesitara agregar un nuevo formato de parámetro, podría
// simplemente crear una nueva subclase, modificando una sola prueba en el método estático getInstance().

// Ortogonalidad-orthogonality
// La combinación perfecta de componentes con responsabilidades definidas de forma precisa que también son
// independientes del sistema más amplio se denomina a veces ortogonalidad. Andrew
// Hunt y David Thomas analizan este tema en su libro, The Pragmatic Programmer,
// 20th Anniversary Edition (Addison-Wesley, 2019).
// Se sostiene que la ortogonalidad promueve la reutilización, ya que los componentes se pueden conectar a nuevos sistemas sin necesidad de ninguna configuración especial. Dichos componentes tendrán
// entradas y salidas claras, independientes de cualquier contexto más amplio. El código ortogonal facilita el cambio porque el impacto de alterar una implementación se localizará en el componente que se está
// alterando. Por último, el código ortogonal es más seguro. Los efectos de los errores deben
// ser de alcance limitado. Un error en un código altamente interdependiente puede causar fácilmente efectos secundarios en el sistema más amplio.
// No hay nada automático en el acoplamiento débil y la alta cohesión en un contexto de clase. Después de todo, podríamos integrar todo nuestro ejemplo procedimental en una clase
// equivocada. ¿Cómo podemos lograr este equilibrio en nuestro código? Normalmente empiezo por considerar las
// clases que deberían estar en mi sistema.