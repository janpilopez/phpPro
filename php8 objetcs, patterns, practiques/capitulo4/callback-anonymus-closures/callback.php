<?php
// Nota: Una devolución de llamada es un bloque de código ejecutable que puede almacenarse en una variable o
// pasarse a métodos y funciones para su invocación posterior.
class Product
{
    public function __construct(public string $name, public float $price)
    {
    }
}
class ProcessSale
{
    private array $callbacks;
    public function registerCallback(callable $callback): void
    {
        $this->callbacks[] = $callback;
    }
    public function sale(Product $product): void
    {
        print "{$product->name}: processing \n";
        foreach ($this->callbacks as $callback) {
            call_user_func($callback, $product);
        }
    }
}

//function logger($product)
// $logger = function ($product) {
// print " logging ({$product->name})\n";
// };
//FUNCION FLECHA ANONIMAS LO MISMO DE ARRIBA
$logger = fn($product) => print " logging ({$product->name})\n";
// La función de flecha es mucho más compacta, pero, como se define una única expresión, es mejor utilizarla para tareas relativamente simples.

$processor = new ProcessSale(); // Instancia de la clase ProcessSale
$processor->registerCallback($logger); // Registro de la función logger en los callback de la clase ProcessSale
$processor->sale(new Product('shoes', 6)); // Llamada al método sale de la clase ProcessSale, la cual ejecuta la función logger tambien registrada en los callback
print "\n";
$processor->sale(new Product('coffee', 6));

// Aquí, creo una función anónima. Es decir, utilizo la palabra clave function en línea
// y sin un nombre de función. Tenga en cuenta que, dado que se trata de una declaración en línea, se requiere un punto y coma
// al final del bloque de código. Mi función anónima se puede almacenar en una
// variable y pasar a funciones y métodos como parámetro. Eso es exactamente lo que hago,
// asigno la función a la variable $logger y la paso al método ProcessSale::r
// egisterCallback(). Finalmente, creo un par de productos y los paso al método
// sale(). Luego, se procesa la venta (en realidad, se imprime un mensaje simple sobre el producto) y se ejecutan todas las devoluciones de llamadas. Aquí está el código en acción:

// PHP 7.4 introdujo una nueva forma de declarar funciones anónimas. Las funciones de flecha
// son funcionalmente muy similares a las funciones anónimas que ya ha encontrado.
// Sin embargo, la sintaxis es mucho más compacta. En lugar de la palabra clave function, se definen con fn, luego paréntesis para una lista de argumentos y, finalmente, en lugar de llaves, un
// operador de flecha (=>) seguido de una única expresión. Esta forma compacta hace que las funciones de flecha sean muy útiles para crear pequeñas devoluciones de llamadas para ordenaciones personalizadas y similares. Aquí,
// reemplazo la función anónima $logger con un equivalente exacto utilizando una función de flecha:

// La función de flecha es mucho más compacta, pero, como se define una única
// expresión, es mejor utilizarla para tareas relativamente simples.
// Por supuesto, las devoluciones de llamadas no tienen por qué ser anónimas. Puede utilizar el nombre de una función, o
// incluso una referencia de objeto y un método, como devolución de llamada. Aquí, hago exactamente eso:
class Mailer
{
    public function doMail(Product $product): void
    {
        print " mailing ({$product->name})\n";
    }
}
echo "\n";
// listing 04.115
$processor = new ProcessSale();
$processor->registerCallback([new Mailer(), 'doMail']); //funcion con nombre si se puede tmbien
$processor->sale(new Product('shoes', 6));
print "\n";
$processor->sale(new Product('coffee', 6));

// Creo una clase: Mailer. Su único método, doMail(), acepta un objeto Product y emite un mensaje al respecto. Cuando llamo a registerCallback(), le paso una matriz. El
// primer elemento es un objeto Mailer y el segundo es una cadena que coincide con el nombre del método que quiero invocar.
// Una devolución de llamada válida en formato de matriz debe tener un objeto como su primer elemento y el nombre de un método como su segundo elemento. Pasé esa prueba aquí y este es mi resultado:

echo '---------------------------RETORNO FUNCION ANONIMA';
// Puedes hacer que un método devuelva una función anónima, algo como esto:
class Totalizer
{
    public static function warnAmount(): callable
    {
        return function (Product $product) {
            if ($product->price > 5) {
                print " reached high price: {$product->price}\n";
            }
        };
    }
}
// listing 04.117
$processor = new ProcessSale();
$processor->registerCallback(Totalizer::warnAmount());
$processor->sale(new Product('shoes', 6));
print "\n";
$processor->sale(new Product('coffee', 6));

echo "---------------------------RETORNO FUNCION ANONIMA 2\n";

class Totalizer2
{
    public static function warnAmount($amt): callable
    {
        $count = 0;

        // Cuando usas & delante de una variable en el contexto de use, estás indicando que esa variable será pasada por referencia en lugar de por valor. Esto significa que cualquier cambio que se haga a la variable dentro de la función anónima también afectará a la variable original fuera de la función.
        return function ($product) use ($amt, &$count) {
            $count += $product->price;
            print " count: $count\n";
            if ($count > $amt) {
                print " high price reached: {$count}\n";
            }
        };
    }
}
// listing 04.119
$processor = new ProcessSale();
$processor->registerCallback(Totalizer2::warnAmount(8));
$processor->sale(new Product('shoes', 6));
print "\n";
$processor->sale(new Product('coffee', 8));
// La función anónima devuelta por Totalizer2::warnAmount() especifica dos
// variables en su cláusula de uso. La primera es $amt. Este es el argumento que warnAmount()
// aceptó. La segunda variable de cierre es $count. $count se declara en el cuerpo de
// warnAmount() y se establece inicialmente en cero. Observe que antepongo un ampersand a la variable $count
// en la cláusula de uso. Esto significa que se accederá a la variable por referencia en lugar de por valor en la función anónima. En el cuerpo de la función anónima,
// incremento $count por el valor del producto y luego pruebo el nuevo total contra $amt. Si se ha alcanzado el
// valor objetivo, emito una notificación.

echo "---------------------------FUNCION FLECHA\n";

$markup = 3;
$counter = fn(Product $product) => print "($product->name) marked up
price: " .
    ($product->price + $markup) .
    "\n";
$processor = new ProcessSale();
$processor->registerCallback($counter);
$processor->sale(new Product('shoes', 6));
print "\n";
$processor->sale(new Product('coffee', 6));
// Puedo acceder a $markup dentro de la función anónima que paso a ProcessSale::sale(). Sin embargo, debido a que la función solo tiene acceso por valor, cualquier
// manipulación que realice dentro de la función no afectará la variable de origen.
echo "---------------------------\n";

// PHP 7.1 introdujo una nueva forma de gestionar los cierres en el contexto de un objeto. El método
// Closure::fromCallable() permite generar un cierre que otorga al código
// que lo invoca acceso a las clases y propiedades de un objeto. Aquí hay una versión de la serie Totalizer
// que utiliza propiedades de objetos para lograr el mismo resultado que el último ejemplo:
class Totalizer3
{
    private float $count = 0;
    private float $amt = 0;
    public function warnAmount(int $amt): callable
    {
        $this->amt = $amt;
        // Closure (una función anónima): es un objeto que encapsula una función o método con su contexto.
        return \Closure::fromCallable([$this, 'processPrice']);
        // Uso de Closure: \Closure::fromCallable([$this, 'processPrice']) permite que el Closure mantenga el contexto de Totalizer3, por lo que puede acceder a las propiedades de la instancia
    }
    private function processPrice(Product $product): void
    {
        $this->count += $product->price;
        print " count: {$this->count}\n";
        if ($this->count > $this->amt) {
            print " high price reached: {$this->count}\n";
        }
    }
}
$totalizer3 = new Totalizer3(); // Crea una instancia de Totalizer3
$processor = new ProcessSale(); // Crea una instancia de ProcessSale
$processor->registerCallback($totalizer3->warnAmount(8)); // Registra el callback con un límite de 8

$processor->sale(new Product("shoes", 6)); // Vende un producto de precio 6
print "\n"; // Imprime una nueva línea

$processor->sale(new Product("coffee", 8)); // Vende un producto de precio 8