<?php
// Examinar argumentos de métodos
// Ahora que las firmas de métodos pueden restringir los tipos de argumentos de objetos, la capacidad de
// examinar los argumentos declarados en una firma de método se vuelve inmensamente útil. La API
// Reflection proporciona la clase ReflectionParameter solo para este propósito. Para obtener un objeto
// ReflectionParameter, necesita la ayuda de un objeto ReflectionMethod. El método Re
// flectionMethod::getParameters() devuelve una matriz de objetos
// ReflectionParameter. También puede crear una instancia de un objeto ReflectionParameter directamente de la manera habitual.
// El constructor de ReflectionParameter requiere un argumento invocable y un
// entero que represente el número del parámetro (indexado por cero) o una cadena que represente
// el nombre del argumento.
// Por lo tanto, las cuatro instancias son equivalentes. Cada una establece un objeto
// ReflectionParameter para el segundo argumento del constructor de la clase
// CdProduct.
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

$classname = CdProduct::class;
$rparam1 = new \ReflectionParameter([$classname, "__construct"], 1);
$rparam2 = new \ReflectionParameter(
    [$classname, "__construct"],
    "firstName"
);
$cd = new CdProduct("cd1", "bob", "bobbleson", 4, 50);
$rparam3 = new \ReflectionParameter([$cd, "__construct"], 1);
$rparam4 = new \ReflectionParameter([$cd, "__construct"], "firstName");

$class = new \ReflectionClass(CdProduct::class);
$method = $class->getMethod("__construct");
$params = $method->getParameters();
foreach ($params as $param) {
    print ClassInfo::argData($param) . "\n";
}

// cotinuacion pagina 196