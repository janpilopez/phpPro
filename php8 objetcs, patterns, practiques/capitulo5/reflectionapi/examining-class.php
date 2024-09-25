<?php
// Examinar una clase
// Un volcado básico de una instancia de ReflectionClass puede proporcionar una gran cantidad de información útil
// para la depuración, pero podemos usar la API de formas más especializadas. Trabajemos
// directamente con las clases de Reflection.
// Ya has visto cómo crear una instancia de un objeto ReflectionClass:
// $prodclass = new \ReflectionClass(CdProduct::class);

class ShopProduct
{
    private int|float $discount = 0;
    public function __construct(
        private string $title,
        private string $producerFirstName,
        private string $producerMainName, //no puede ser accedido por las clases hijas
        protected int|float $price /* puede ser accedido por las clases hijas */,
    ) {}
    public function getProducerFirstName(): string
    {
        return $this->producerFirstName;
    }
    public function getProducerMainName(): string
    {
        return $this->producerMainName;
    }
    public function setDiscount(int|float $num): void
    {
        //esto puede ser redundante, pero es una buena practica como documentacion en linea
        $this->discount = $num;
    }
    public function getDiscount(): int
    {
        return $this->discount;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getPrice(): int|float
    {
        return $this->price - $this->discount;
    }
    public function getProducer(): string
    {
        return $this->producerFirstName . ' ' . $this->producerMainName;
    }
    public function getSummaryLine(): string
    {
        $base = "{$this->title} ( {$this->producerMainName}, ";
        $base .= "{$this->producerFirstName} )";
        return $base;
    }
}
class CdProduct extends ShopProduct
{
    //estamos declarando una propiedad adicional playLength
    public function __construct(string $title, string $firstName, string $mainName, int|float $price, private int $playLength)
    {
        parent::__construct($title, $firstName, $mainName, $price);
    }
    public function getPlayLength(): int
    {
        return $this->playLength;
    }
    public function getSummaryLine(): string
    {
        $base = "{$this->title} ( {$this->producerMainName}, ";
        $base .= "{$this->producerFirstName} )";
        $base .= ": playing time - {$this->playLength}";
        return $base;
    }
    public static function getData(\ReflectionClass $class): string
    {
        $details = "";
        $name = $class->getName();
        $details .= ($class->isUserDefined()) ?
            "$name is user defined\n" : "";
        $details .= ($class->isInternal()) ?
            "$name is built-in\n" : "";
        $details .= ($class->isInterface()) ?
            "$name is interface\n" : "";
        $details .= ($class->isAbstract()) ?
            "$name is an abstract class\n" : "";
        $details .= ($class->isFinal()) ?
            "$name is a final class\n" : "";
        $details .= ($class->isInstantiable()) ?
            "$name can be instantiated\n" : "$name can not be instantiated\n";
        $details .= ($class->isCloneable()) ?
            "$name can be cloned\n" : "$name can not be cloned\n";
        return $details;
    }
}

$prodclass = new \ReflectionClass(CdProduct::class);
print CdProduct::getData($prodclass);
// Creo un objeto ReflectionClass y lo asigno a una variable llamada $prodclass
// pasando el nombre de la clase CdProduct al constructor de ReflectionClass. Luego, $prodclass
// se pasa a un método llamado ClassInfo::classData() que demuestra algunos de los métodos que se pueden usar para consultar una clase.

// Los métodos deberían explicarse por sí solos, pero aquí hay una breve descripción de algunos de ellos:

// ReflectionClass::getName() devuelve el nombre de la clase que se está
// examinando.
// • El método ReflectionClass::isUserDefined() devuelve verdadero si la clase se ha declarado en código PHP y
// ReflectionClass::isInternal() devuelve verdadero si la clase está incorporada.
// • Puede probar si una clase es abstracta con
// ReflectionClass::isAbstract() y si es una interfaz con
// ReflectionClass::isInterface().
// • Si desea obtener una instancia de la clase, puede probar la viabilidad
// de ello con ReflectionClass::isInstantiable().
// • Puede comprobar si una clase se puede clonar con el método
// ReflectionClass::isCloneable().
// • Incluso puede examinar el código fuente de una clase definida por el usuario. El objeto
// ReflectionClass proporciona acceso al nombre de archivo de su clase y a las líneas de inicio y fin de la clase en el archivo.

// A continuación, se muestra un método rápido y sencillo que utiliza ReflectionClass para acceder al código fuente de una
// clase:

class ReflectionUtil
{
    public static function getClassSource(\ReflectionClass $class): string
    {
        $path = $class->getFileName();
        $lines = @file($path);
        $from = $class->getStartLine();
        $to = $class->getEndLine();
        $len = $to - $from + 1;
        return implode(array_slice($lines, $from - 1, $len));
    }
}
print ReflectionUtil::getClassSource(
    new \ReflectionClass(CdProduct::class)
);
// ReflectionUtil es una clase simple con un único método estático, ReflectionUtil::get
// ClassSource(). Ese método toma un objeto ReflectionClass como su único argumento y
// devuelve el código fuente de la clase referenciada. ReflectionClass::getFileName() proporciona
// la ruta al archivo de la clase como una ruta absoluta, por lo que el código debería poder continuar
// y abrirlo. file() obtiene una matriz de todas las líneas del archivo. ReflectionClass::getStartLine() proporciona la línea de inicio de la clase; ReflectionClass::getEndLine() encuentra la línea
// final. A partir de ahí, es simplemente una cuestión de usar array_slice() para extraer las líneas de interés.
// Para resumir, este código omite el manejo de errores (al colocar el carácter @
// delante de la llamada a file()). En una aplicación del mundo real, querría verificar los argumentos
// y los códigos de resultados.