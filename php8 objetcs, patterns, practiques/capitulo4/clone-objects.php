<?php
// In PHP 4, copying an object was a simple matter of assigning from one variable to another:
class CopyMe
{
}

$first = new CopyMe();
$second = $first;
// PHP 4: $second and $first are 2 distinct objects

// PHP 5 plus: $second and $first refer to one object (son dos objetos distintos)

// Este “asunto simple” fue una fuente de muchos errores, ya que se generaban copias de objetos accidentalmente
// cuando se asignaban variables, se llamaban métodos y se devolvían objetos. Esto se agravó por el hecho de que no había forma de probar dos variables
// para ver si hacían referencia al mismo objeto. Las pruebas de equivalencia le indicarían si
// todos los campos eran iguales (==) o si ambas variables eran objetos (===), pero no
// si apuntaban al mismo objeto.
// En PHP, una variable que parece contener un objeto, de hecho, contiene un identificador que
// hace referencia a la estructura de datos subyacente. Cuando se asigna o se pasa una variable de este tipo
// a un método, se copia el identificador que contiene. Sin embargo, cada copia continúa
// apuntando al mismo objeto. Esto significa que, en mi ejemplo anterior, $first y $second
// contienen identificadores que apuntan al mismo objeto en lugar de dos copias del objeto.
// Aunque esto es generalmente lo que desea cuando trabaja con objetos, habrá ocasiones en las que necesitará obtener una copia de un objeto.
// PHP proporciona la palabra clave clone solo para este propósito. clone opera sobre una instancia de objeto
// y produce una copia por valor:

$first = new CopyMe();
$second = clone $first;

class Person
{
    private int $id = 0;
    public function __construct(private string $name, private $age)
    {
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function __clone(): void
    {
        $this->id = 0;
    }
}

$person = new Person('bob', 44);
$person->setId(343);
$person2 = clone $person;
// Cuando se invoca clone en un objeto Person, se realiza una nueva copia superficial y se invoca su método __
// clone(). Esto significa que todo lo que hago en __clone() sobrescribe la
// copia predeterminada que ya hice. En este caso, me aseguro de que la propiedad $id del objeto copiado esté
// establecida en cero:
// var_dump($person2); //la persona clona toma la instancia con el id 0, ya que el clonar se ejecuta la funcion __clone
// var_dump($person); //la persona clona toma la instancia principal con el id 434

// Una copia superficial garantiza que las propiedades primitivas se copien del objeto antiguo al nuevo. Las propiedades del objeto tienen sus identificadores copiados pero no sus datos subyacentes,
// aunque esto puede no ser lo que desea o espera al clonar un objeto. Digamos que
// le doy al objeto Persona una propiedad de objeto Cuenta. Este objeto contiene un saldo que quiero
// copiar al objeto clonado. Sin embargo, lo que no quiero es que ambos objetos Persona
// contengan referencias a la misma cuenta:

class Account
{
    public function __construct(public float $balance)
    {
    }
}
// listing 04.102
class PersonV2
{
    private int $id;
    public function __construct(private string $name, private int $age, public Account $account)
    {
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function __clone(): void
    {
        $this->id = 0;
        //este lo desabilitamos para no compartir la cuenta
        $this->account = clone $this->account;
        //si la comentamos apuntaria a la misma cuenta y por tato al mismo saldo
    }
}
// listing 04.103
$person = new PersonV2('bob', 44, new Account(200));
$person->setId(343);
$person2 = clone $person;
// give $person some money
$person->account->balance += 10;
// $person2 sees the credit too
print $person2->account->balance. "\n";
print $person->account->balance;

// $person contiene una referencia a un objeto Account que he mantenido accesible públicamente
// por razones de brevedad (como sabes, normalmente restringiría el acceso a una propiedad,
// proporcionando un método de acceso, si es necesario). Cuando se crea el clon, contiene una
// referencia al mismo objeto Account al que hace referencia $person. Demuestro esto agregando
// a la Account del objeto $person y confirmando el saldo aumentado a través de
// $person2.
// Si no quiero que se comparta una propiedad de objeto después de una operación de clonación, 
//entonces depende de mí clonarla explícitamente en el método __clone():
