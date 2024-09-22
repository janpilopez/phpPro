<?php
// Another Java-inspired feature introduced by PHP 5 was the __toString() method.
// Before PHP 5.2, when you printed an object, it would resolve to a string like this:
// class StringThing
// {
// }
// $st = new StringThing();
// print $st;
// Since PHP 5.2, this code will produce an error like this:
// Object of class popp\ch04\batch22\StringThing could not be converted to string

// after php 5.2
class Person
{
    public function getName(): string
    {
        return 'Bob';
    }
    public function getAge(): int
    {
        return 44;
    }
    ##CLASE IMPORTANTE AGREGADA
    public function __toString(): string
    {
        //se puede decir que esto implementa la clase Stringable
        $desc = $this->getName() . ' (age ';
        $desc .= $this->getAge() . ')';
        return $desc;
    }


    public static function printThing(string|\Stringable $str): void
    {
        // El método __toString() es particularmente útil para el registro y el informe de errores, así como
        // para las clases cuya tarea principal es transmitir información. La clase Exception, por
        // ejemplo, resume los datos de excepción en su método __toString().
        // A partir de PHP 8, cualquier clase que implemente un método __toString() se declara implícitamente
        // como que implementa la interfaz Stringable incorporada. Eso significa que puede usar una declaración de tipo de unión para restringir argumentos y propiedades. Aquí hay un ejemplo:
        print $str;
    }
    // Podríamos pasar una cadena o nuestro objeto Person al método printThing(), y
    // aceptaría felizmente cualquiera de los dos, seguro de saber que podría funcionar con cualquier cosa
    // que le enviemos en cualquier forma similar a una cadena que elija.
}
$person = new Person();
print $person; //por defecto busca el metodo __toString() para imprimir el objeto
//si no esta funcion __toString() obtendremos un error

print $person->printThing('Hello, World!'); // Hello, World!
// Some Other Type-Checking Functions
// We have seen variable handling functions that check for primitive types. While we are
// checking on the contents of our variables, it is worth mentioning a few functions that go
// beyond checking primitive types to provide more general information about ways that
// data held in a variable might be used. I list these in Table 3-2.
// 791 / 5.000


