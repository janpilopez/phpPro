<?php
// Obtención de una referencia de cadena completamente calificada a una clase

namespace mypackage;

use util as u;
use util\db\Querier as q;

class Local {}
// Resolve these:
// Aliased namespace
// u\Writer;
// Aliased class
// q;
// Class referenced in local context
// Local

print u\Writer::class . "\n";
print q::class . "\n";
print Local::class . "\n";
// The preceding snippet outputs this:
// util\Writer
// util\db\Querier
// mypackage\Local

// A partir de PHP 8, también puedes llamar a ::class en un objeto. Por ejemplo, dada una
// instancia de ShopProduct, puedo obtener el nombre completo de la clase de esta manera:
$bookp = new BookProduct(
    "Catch 22",
    "Joseph",
    "Heller",
    11.99,
    300
   );
print $bookp::class;
// Tenga en cuenta que esta sintaxis conveniente no ofrece nuevas funciones: ya ha encontrado la función get_class() que logra el mismo resultado.


// Learning About Methods
// Puede obtener una lista de todos los métodos de una clase mediante la función get_class_methods(). Esto requiere un nombre de clase y devuelve una matriz que contiene los nombres de todos los métodos de la clase:
print_r(get_class_methods('\\popp\\ch04\\batch02\\BookProduct'));
// Assuming the BookProduct class exists, you might see something like this:
// Array
// (
//  [0] => __construct
//  [1] => getNumberOfPages
//  [2] => getSummaryLine
//  [3] => getPrice
// Solo los nombres de los métodos públicos se incluirán en la lista devuelta.
// Como ha visto, puede almacenar un nombre de método en una variable de cadena e invocarlo dinámicamente junto con un objeto, de esta manera:
$product = self::getProduct();
$method = "getTitle"; // define a method name
print $product->$method(); // invoke the method

if (in_array($method, get_class_methods($product))) {
    print $product->$method(); // invoke the method
}
// PHP proporciona herramientas más especializadas para este propósito (verificar la existencia del metodo). 
//Puede comprobar los nombres de los métodos
// hasta cierto punto con dos funciones: is_callable() y method_exists().
// is_callable() es la más sofisticada de las dos funciones. Acepta una variable de cadena
// que representa un nombre de función como su primer argumento y devuelve verdadero si la función existe
// y puede ser invocada. Para aplicar la misma prueba a un método, debe pasarle una matriz en
// lugar del nombre de la función. La matriz debe contener un nombre de objeto o clase como su primer
// elemento y el nombre del método a comprobar como su segundo elemento. La función devolverá
// verdadero si el método existe en la clase:
if (is_callable([$product, $method])) {
    print $product->$method(); // invoke the method
}

// is_callable() acepta opcionalmente un segundo argumento, un valor booleano. Si lo establece como
// verdadero, la función solo comprobará la sintaxis del nombre de método o función dado, no
// su existencia real. También acepta un tercer argumento opcional que debe ser una
// variable. Si se proporciona, se completará con una representación en cadena del invocable proporcionado.
// Aquí, invoco is_callable() con ese tercer argumento opcional que luego genero:

if (is_callable([$product, $method], false, $callableName)) {
    print $callableName;//con el tenero argumento devuelve el nombre completo con el metodo
}
// And here is my output:
// popp\ch05\batch05\CdProduct::getTitle
// Esta funcionalidad puede resultar útil para fines de documentación o registro.

// La función method_exists() requiere un objeto (o un nombre de clase) y un nombre de método y devuelve verdadero si el método dado existe en la clase del objeto:
if (method_exists($product, $method)) {
    print $product->$method(); // invocar el método
}
// Recuerde que el hecho de que exista un método no significa que
// será invocable. method_exists() devuelve verdadero para los métodos privados y protegidos, así como para los públicos.

#RESUMEN DE LA VERIFICACION DE MEOTODOS
// IS_CALLABLE SOLO PUBLICOS RETORNA TRUE
// METHOD_EXISTS PUBLICOS, PRIVADOS Y PROTEGIDOS RETORNA TRUE PARA TODOS

// Learning About Properties

// Puede obtener una lista de todas las propiedades(metodos) de una clase mediante la función get_class_vars(). Esto requiere un nombre de clase y devuelve una matriz que contiene los nombres de todas las propiedades de la clase:
// listing 05.52
print_r(get_class_vars('\\popp\\ch05\\batch05\\CdProduct'));
// Only the public property is shown:
// Array (
    // [coverUrl] => cover url
// )

// Learning About Inheritance
// Las funciones de clase también nos permiten representar gráficamente las relaciones de herencia. Podemos encontrar el padre de una clase, por ejemplo, con get_parent_class(). Esta función requiere un objeto o un nombre de clase, y devuelve el nombre de la superclase, si existe. Si no existe dicha clase (es decir, si la clase que estamos probando no tiene un padre), entonces la función devuelve falso.
print get_parent_class('\\popp\\ch04\\batch02\\BookProduct');

// También podemos probar si una clase es descendiente de otra usando la función is_subclass_
// of(). Esto requiere un objeto hijo (o el nombre de una clase) y el nombre de la
// clase padre. La función devuelve verdadero si el segundo argumento es una superclase del primer argumento:
$product = self::getBookProduct(); // acquire an object
if (is_subclass_of($product, '\\popp\\ch04\\batch02\\ShopProduct')) {
 print "BookProduct is a subclass of ShopProduct\n";
}
// is_subclass_of() solo le informará sobre las relaciones de herencia de clases. No
// le informará que una clase implementa una interfaz. Para eso, debe utilizar el operador instanceof.
// O puede utilizar una función que sea parte de la SPL (biblioteca estándar de PHP).
// class_implements() acepta un nombre de clase o una referencia de objeto y devuelve una matriz de
// nombres de interfaz:
// listing 05.55
if (in_array(needle: 'someInterface', haystack: class_implements($product))) {
    print "BookProduct is an interface of someInterface\n";
}
// class_implements($product): Esta función devuelve un array con las interfaces que implementa la clase $product.
// in_array(needle: 'someInterface', haystack: ...): Esta función comprueba si el valor 'someInterface' está presente en el array devuelto por class_implements.
// Si 'someInterface' está en el array, se ejecuta el código dentro del bloque, que imprime el mensaje "BookProduct is an interface of someInterface"