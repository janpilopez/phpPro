<?php
// To refer to a method in the context of a class rather than an object, you use :: rather than ->:
// parent::__construct()
// El fragmento anterior significa “Invocar el método __construct() de la clase padre”.
//SEGUN LO DE ARRIBA HEREDAMOS LOS METODOS Y PROPIEDADES DE LA CLASE PADRE CON parent::__construct(), SOLO CON CONSTRUCT __ VA SIGNO DE DOBLE GUION BAJO
//LOS HIJOS PUEDEN HEREDAR LOS METDOS Y PROPIEDADES DE LA CLASE PADRE,
//SIN EMBARGO SI EXISTE UN METODO CON EL MISMO NOMBRE EN LA CLASE HIJA, ESTE METODO SE SOBRESCRIBE Y PREVALECERA EL DEL HIJO
class ShopProduct
{
    public $title;
    public $producerMainName;
    public $producerFirstName;
    public $price;
    public function __construct($title, $firstName, $mainName, $price)
    {
        $this->title = $title;
        $this->producerFirstName = $firstName;
        $this->producerMainName = $mainName;
        $this->price = $price;
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
class BookProduct extends ShopProduct
{
    //agregamos la propiedades especificas de cada clase hija, que solo estaran disponibles en la clase hija
    public $numPages;
    public function __construct(string $title, string $firstName, string $mainName, float $price, int $numPages)
    {
        parent::__construct($title, $firstName, $mainName, $price); //especificamos la invocacion del constructor de la clase padre
        $this->numPages = $numPages; //agregamos la propiedad especifica de la clase hija para el constructor clase hija
    }
    public function getNumberOfPages(): int
    {
        return $this->numPages;
    }
    public function getSummaryLine(): string
    {
        #TODO: Herencia HIJOS
        //BEFORE
        // $base = "{$this->title} ( $this->producerMainName, ";
        // $base .= "$this->producerFirstName )";

        //AFTER
        $base = parent::getSummaryLine();
        $base .= ": page count - {$this->numPages}";
        return $base;
    }
}
// Cada clase secundaria invoca al constructor de su clase principal antes de establecer sus propias propiedades. La clase base ahora solo conoce sus propios datos. Las clases secundarias son generalmente
// especializaciones de sus clases principales. Como regla general, debe evitar dar a las clases principales conocimiento especial sobre sus clases secundarias.
class CdProduct extends ShopProduct
{
    public $playLength;
    public function __construct(string $title, string $firstName, string $mainName, float $price, int $playLength)
    {
        parent::__construct($title, $firstName, $mainName, $price);
        $this->playLength = $playLength;
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
}

// Public, Private, and Protected: Managing Access to Your Classes
// • Public properties and methods can be accessed from any context.
//• A private method or property can only be accessed from within the enclosing class. Even subclasses have no access.
// • A protected method or property can only be accessed from within either the enclosing class or from a subclass. No external code is granted access

class ShopProductWriter
{
    public $products = [];
    public function addProduct(ShopProduct $shopProduct): void
    {
        $this->products[] = $shopProduct;
    }
    public function write(): void
    {
        $str = '';
        foreach ($this->products as $shopProduct) {
            $str .= "{$shopProduct->title}: ";
            $str .= $shopProduct->getProducer();
            $str .= " ({$shopProduct->getPrice()})\n";
        }
        print $str;
    }
}
// The ShopProductWriter class is now much more useful. It can hold many
// ShopProduct objects and write data for them all in one go. I must trust my client coders
// to respect the intentions of the class, though. Despite the fact that I have provided an
// addProduct() method, I have not prevented programmers from manipulating the
// $products property directly. Not only could someone add the wrong kind of object to
// the $products array property, but he could even overwrite the entire array and replace it
// with a primitive value. I can prevent this by making the $products property private:

// class ShopProductWriter
// {
//  private $products = [];
//...
// }
