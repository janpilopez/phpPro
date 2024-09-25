<?php
// Así como ReflectionClass se utiliza para examinar una clase, un objeto ReflectionMethod
// examina un método.
// Puede obtener una matriz de objetos ReflectionMethod de ReflectionClass
// s::getMethods(). Alternativamente, si necesita trabajar con un método específico,
// ReflectionClass::getMethod() acepta un nombre de método y devuelve el objeto
// ReflectionMethod relevante.
// También puede crear una instancia de ReflectionMethod directamente, pasándole una cadena de clase/método, el nombre de la clase y el nombre del método, o un objeto y un nombre de método.
// Así es como podrían verse esas variaciones:

class ClassInfo
{
    public static function methodData(\ReflectionMethod $method): string
    {
        $details = "";
        $name = $method->getName();
        $details .= ($method->isUserDefined()) ?
            "$name is user defined\n" : "";
        $details .= ($method->isInternal()) ? "$name is built-in\n" : "";
        $details .= ($method->isAbstract()) ?
            "$name is an abstract class\n" : "";
        $details .= ($method->isPublic()) ? "$name is public\n" : "";
        $details .= ($method->isProtected()) ?
            "$name is protected\n" : "";
        $details .= ($method->isPrivate()) ? "$name is private\n" : "";
        $details .= ($method->isStatic()) ? "$name is static\n" : "";
        $details .= ($method->isFinal()) ? "$name is final\n" : "";
        $details .= ($method->isConstructor()) ?
            "$name is the constructor\n" : "";
        $details .= ($method->returnsReference()) ?
            "$name returns a reference (as opposed to a value)\n" : "";
        return $details;
    }
}

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


$cd = new CdProduct("cd1", "bob", "bobbleson", 4, 50);
$classname = CdProduct::class;
$rmethod1 = new \ReflectionMethod("{$classname}::__construct");
// class/method string
$rmethod2 = new \ReflectionMethod($classname, "__construct");
// class name and method name
$rmethod3 = new \ReflectionMethod($cd, "__construct");
// object and method name

// Aquí, usamos ReflectionClass::getMethods() para poner a prueba la clase ReflectionMethod
$prodclass = new \ReflectionClass(CdProduct::class);
// Obtener métodos de la clase
$methods = $prodclass->getMethods();
foreach ($methods as $method) {
    print ClassInfo::methodData($method);
    print "\n----\n";
}

#SEGUNDA OPCION SIN NECESIDAD DE CREAR UNA CLASE
// foreach ($methods as $method) {
//     echo "Method Name: " . $method->getName() . "\n";
//     echo "Visibility: " . ($method->isPublic() ? "Public" : ($method->isProtected() ? "Protected" : "Private")) . "\n";
//     echo "Is Static: " . ($method->isStatic() ? "Yes" : "No") . "\n";
//     echo "Is Final: " . ($method->isFinal() ? "Yes" : "No") . "\n";
//     echo "Is Abstract: " . ($method->isAbstract() ? "Yes" : "No") . "\n";
//     echo "----\n";
// }


class ReflectionUtil
{
    public static function getMethodSource(\ReflectionMethod $method): string
    {
        $path = $method->getFileName();
        $lines = @file($path);
        $from = $method->getStartLine();
        $to = $method->getEndLine();
        $len = $to - $from + 1;
        return implode(array_slice($lines, $from - 1, $len));
    }
}
$class = new \ReflectionClass(CdProduct::class);
$method = $class->getMethod('getSummaryLine');
print ReflectionUtil::getMethodSource($method);
// Este código define una clase llamada ReflectionUtil que contiene un método estático getMethodSource. Este método recibe una instancia de \ReflectionMethod como parámetro y devuelve el código fuente de ese método en forma de cadena.
// 
// Aquí tienes un desglose de lo que hace cada parte del código:
// 
    // ReflectionUtil Clase: Esta clase contiene utilidades para trabajar con reflexiones en PHP.
// 
    // getMethodSource Método:
        // Toma como argumento una instancia de \ReflectionMethod.
        // Usa el método getFileName() para obtener la ruta del archivo donde está definido el método.
        // Usa file() para leer todas las líneas del archivo en un array.
        // Obtiene el número de línea de inicio (getStartLine()) y de fin (getEndLine()) del método.
        // Calcula la cantidad de líneas que debe extraer.
        // Usa array_slice() para obtener solo las líneas que corresponden al método y luego las convierte en una cadena con implode().
// 
    // Reflexión de Clase y Método:
        // Se crea una instancia de \ReflectionClass para la clase CdProduct.
        // Se obtiene el método getSummaryLine usando getMethod().
        // Finalmente, se imprime el código fuente del método getSummaryLine utilizando ReflectionUtil::getMethodSource.