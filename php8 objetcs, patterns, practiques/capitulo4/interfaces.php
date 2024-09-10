<?php

// Interfaces
// Aunque las clases abstractas permiten proporcionar cierta medida de implementación, las interfaces
// son plantillas puras. Una interfaz solo puede definir funcionalidad; nunca puede implementarla. Una interfaz se declara con la palabra clave interface. Puede contener propiedades y
// declaraciones de métodos, pero no cuerpos de métodos.
// A continuación se muestra una interfaz:

interface Chargeable
{
    public function getPrice(): float;//si o si debe ser implementado en las clases que implementen esta interfaz
}

interface Bookable
{
    public function getNumPages(): float;
}

// Una clase puede extender una superclase e implementar cualquier número de interfaces.
//La cláusula extends debe preceder a la cláusula implements
class ShopProduct implements Chargeable
{
    // ...
    protected float $price;
    // ...
    public function getPrice(): float
    {
        return $this->price;
    }
    // ...
}

function cdInfo(CdProduct $prod): int
{
    // we know we can call getPlayLength()
    $length = $prod->getPlayLength();
    //PODEMOS ACCEDER A getPlayLength() PORQUE ESTAMOS INSTANCIANDO UN OBJETO DE TIPO CdProduct Y ESTE TIENE EL METODO getPlayLength()
    //el cd producto tiene acceso por herencia cdproduct -> shopproduct -> chargeable
}

// This means that the CdProduct class belongs to the following:
// CdProduct
// ShopProduct
// Chargeable
// El método sabe que el objeto $prod tiene un método getPlayLength() además de todos los métodos definidos en la clase ShopProduct y la interfaz Chargeable.
// Sin embargo, un método con un requisito de tipo más genérico (ShopProduct en lugar de CdProduct) solo puede saber que el objeto proporcionado contiene métodos de ShopProduct.

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
}

function addProduct(ShopProduct $prod)
{
    // even if $prod is a CdProduct object
    // we don't *know* this -- so we can't
    // presume to use getPlayLength()
    // no tenemos acceso a getPlayLength() porque estamos instanciando un objeto de tipo ShopProduct
}

function addChargeableItem(Chargeable $item)
{
    // all we know about $item is that it
    // is a Chargeable object -- the fact that it
    // is also a CdProduct object is irrelevant.
    // We can only be sure of getPrice()
    // solo podemos estar seguros de getPrice() que hereda de la interfaz
}

class Shipping implements Chargeable
{
    public function __construct(private float $price)
    {
    }
    public function getPrice(): float
    {
        return $this->price;
    }
}


class TimedService implements Bookable
{
    public function __construct(private float $numPages)
    {
    }
    //las clases que implementen esta interfaz deben implementar obligatoriamente los metodos en este caso getNumPages
    public function getNumPages(): float
    {
        return $this->numPages;
    }
}
// A class can both extend a superclass and implement any number of interfaces. The extends clause should precede the implements clause:
class Consultancy extends TimedService implements Chargeable,Bookable
{
    public function __construct(private float $price)
    {
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getNumPages(): float//no es obligatorio implementar el metodo de la interfaz secundaria
    {
        return 0;//algoo
    }
}


// Tenga en cuenta que la clase Consultancy implementa más de una interfaz. Varias interfaces
// aparecen después de la palabra clave implements en una lista separada por comas. PHP solo admite la herencia de un solo padre, por lo que la palabra clave extends puede
// preceder solo a un nombre de clase.