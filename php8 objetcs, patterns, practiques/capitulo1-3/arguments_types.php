<?php

// declare(strict_types=1);//con esto habilitamos la parte final para que no permitan valores parecidos o los convierta a su tipo

// e. A ShopProduct object, therefore, belongs to the primitive type
// object, but it also belongs to the ShopProduct class type. In this section, I will look at
// types of both kinds in relation to class methods.

// TIPOS PRIMITIVOS - NO ES UN LENGUAJE FUERTEMENTE TIPADO
// PHP is a loosely typed language. This means that there is no necessity for a variable to be
// declared to hold a particular data type. The variable $number could hold the value 2 and the string "two" within the same scope
// Puede determinar el tipo del valor de una variable utilizando una de las funciones de verificación de tipos de PHP
$cadena = 'Hola';
is_bool($cadena); // false
is_integer($cadena); // false
is_float($cadena); // false A floating-point number (a number with a decimalpoint). Alias of is_double()
is_double($cadena); // false
is_string($cadena); // true
is_object($cadena); // false
is_resource($cadena); // false A handle for identifying and working with external resources such as databases or files
is_array($cadena); // false
is_null($cadena); // false An unassigned value

// Funciones de verificación de pseudotipos

// is_countable($cadena) //Una matriz o un objeto que se puede pasar a la función count()
// is_iterable($cadena) //Una estructura de datos atravesable, es decir, una que se puede recorrer en bucle utilizando foreach
// is_callable($cadena) //Código que se puede invocar, a menudo una función anónima o un nombre de función
// is_numeric($cadena) //Un int, un long o una cadena que se puede resolver en un número

//EN ESTE EJEMPLO MOSTRAMOS LOS POSIBLES ERRORES DEL TIPO DE DATOS SI NECESITAMOS EVALUAR UN BOLEEANO Y LA POSIBLE SOLUCION
class AddressManager
{
    private $addresses = ['209.131.36.159', '216.58.213.174'];
    /**
     * Outputs the list of addresses.
     * If $resolve is true then each address will be resolved
     * @param $resolve boolean Resolve the address?
     */
    //PODEMOS DEFINIR ESTAS INDICACIONES PARA EVITAR QUE INGRESEN PAREMETROS INCORRECTOS
    public function outputAddresses($resolve)
    {
        foreach ($this->addresses as $address) {
            print $address;
            if ($resolve) {
                print ' (' . gethostbyaddr($address) . ')';
            }
            print "\n";
        }
    }
}

$resolve = 'false';

$address = new AddressManager();
$address->outputAddresses($resolve);
echo 'FINAL';

#EQUIVALENCIA
if ('false') {
    // ...
}
//    It is actually equivalent to this:
if (true) {
    // ...
}
#SOLUCION AL PROBLEMA ANTERIOR
if (is_string($resolve)) {
    //. Para tipos primitivos como Boolean, realmente solo había una forma de hacer esto antes del lanzamiento de PHP 7. Tendría que escribir código
    $resolve = preg_match("/^(false|no|off)$/i", $resolve) ? false : true;
    echo "\n" . var_dump($resolve) . ''; // no imprime
} else {
    echo 'NO PASO';
}

#Type Declarations: Primitive Types
function tipesInFunctions(string $title, string $firstName, string $mainName, float $price)
{
    // Con el método constructor reforzado de esta manera, puedo estar seguro de que los argumentos $title,
    // $firstName y $mainName siempre contendrán datos de cadena y que $price contendrá un valor de punto flotante.
}

?>


<!-- declaraciones de tipo estrictas -->
<?php
function speak(string $name): string
{
    return "Hello {$name}!";
}
echo speak(1); // This fires the error "Uncaught TypeError" //si activamos la declaracion de tipo estricto declare(strict_types=1); no permitira que se ejecute, siempre se declara al inicio de todo
//no permitira la  ejecucion porque el valor que se le pasa no es del tipo string
echo speak('Valerio'); // This prints "Hello Valerio!"
// una declaración strict_types se aplica al archivo desde el que se realiza una llamada,
// y no al archivo en el que se implementa una función o un método. Por lo tanto, depende del código
// del cliente hacer cumplir la estrictez

function getValues(array $default = [])
{
    $values = [];
    // do something to get values
    // merge the provided defaults (it will always be an array)
    $values = array_merge($default, $values);
    return $values;
}

// MIXED TIPES El valor mixto elimina la duda y la incertidumbre, y por esa razón es útil. , POR TANTO ESPECIFICAMOS QUE PUEDE RECIBIR CUALQUIER COSA
//NOT DIFFERENCE
// public function add(string $key, $value)
// public function add(string $key, mixed $value)