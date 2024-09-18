<?php
// Como hemos visto, las interfaces te ayudan a manejar el hecho de que, al igual que Java, PHP no
// soporta herencia múltiple. En otras palabras, una clase en PHP solo puede extender un único padre. Sin embargo, puedes hacer que una clase prometa implementar tantas interfaces como quieras; para cada interfaz que implementa, la clase toma el tipo correspondiente.
// Por lo tanto, las interfaces proporcionan tipos sin implementación. Pero, ¿qué sucede si quieres compartir una
// implementación a través de jerarquías de herencia? PHP 5.4 introdujo rasgos, y estos te permiten hacer exactamente eso.
// Un rasgo es una estructura similar a una clase que no se puede instanciar por sí misma, pero se puede
// incorporar en clases. Cualquier método definido en un rasgo se vuelve disponible como parte de cualquier clase que lo use. Un rasgo cambia la estructura de una clase, pero no cambia su tipo.
// Piensa en los rasgos como inclusiones para clases.
// Veamos por qué un rasgo(trait) puede ser útil.



abstract class Service
{
    // service oriented stuff
}
class ShopProduct
{
    use PriceUtilities;
}

class UtilityService extends Service
{
    use PriceUtilities;
}

//USANDO TRAITS
// En PHP, un trait es una característica del lenguaje que permite la reutilización de métodos en diferentes clases. Los traits son útiles cuando quieres
// compartir métodos entre múltiples clases sin tener que usar la herencia múltiple, que PHP no soporta directamente como JAVA
trait PriceUtilities
{
    private $taxrate = 20;
    public function calculateTax(float $price): float
    {
        return ($this->taxrate / 100) * $price;
    }
}

$p = new ShopProduct();
print $p->calculateTax(100) . "\n";
$u = new UtilityService();
print $u->calculateTax(100) . "\n";


// Uso de más de un atributo
// Puede incluir varios atributos en una clase enumerando cada uno después de la palabra clave use, // separados por comas. En este ejemplo, defino y aplico un nuevo atributo, IdentityTrait, // manteniendo mi atributo PriceUtilities original:
trait IdentityTrait
{
    public function generateId(): string
    {
        return uniqid();
    }
}
class ShopProductMore
{
    use PriceUtilities;
    use IdentityTrait;
}//PODEMOS USAR VARIOS TRAITS EN UNA CLASE

//other example:  Definición de un Trait
// Un trait se define con la palabra clave trait. Dentro del trait, puedes definir métodos y propiedades que pueden ser compartidos por otras clases. A diferencia de las clases, los traits no pueden ser instanciados por sí mismos.

trait Logger
{
    public function log($message)
    {
        echo "Log: $message";
    }
}

// Uso de Traits en una Clase Para usar un trait en una clase, utilizas la palabra clave use dentro de la definición de la clase. Los métodos del trait se integran en la clase como si fueran parte de ella.
class User
{
    use Logger;
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

$user = new User('John Doe');
$user->log('User created\n'); // Salida: Log: User created

// MULTIPLES TRAIS
trait TraitA
{
    public function doSomething()
    {
        echo 'Doing something in TraitA';
    }
}

trait TraitB
{
    public function doSomething()
    {
        echo 'Doing something in TraitB';
    }
}

class Example
{
    // use TraitA, TraitB {
    //     TraitB::doSomething insteadof TraitA; // Usa el método doSomething de TraitB, sobreescribe el método doSomething de TraitA y usa el B
    // }
    // $example = new Example();
    // $example->doSomething(); // Salida: Doing something in TraitB


    //EN ESTE CASO PARA NO ANULARLO LE UBICAMOS UN ALIAS SI TIENE EL MISMO NOMBRE PARA PODER USARLOS DOS
    use TraitA;
    use TraitB {
        TraitB::doSomething insteadof TraitA;//INDICAMOS LO MISMO DE ARRIBA SOBREESCRIBIMOS Y DEJAMOS EL DEL B
        TraitA::doSomething as doSomethingV2; //ESPECIFICAMOS ALIAS AL DEL A
        //ESPECIFICAMOS QUE EL TRAIT A SE LLAME DO SOMETHING V2, EL TRAIT B SE MANTENDRA CON SU NOMBRE ORIGINAL
        //NO CONFUNDIR AL PENSAR QUE AL DECLARAR SOLO TRAIT_A SE VA A USAR EL METODO DO SOMETHING DE TRAIT A
        //LUEGO VIENE LA CONFIGURACION DE OVERRIDE
    }
}

$example = new Example();
echo "\n";
$example->doSomething();//B
echo "\n";
$example->doSomethingV2();//A
