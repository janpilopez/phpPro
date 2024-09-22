<?php
// Claro, con la implementación completa de la clase `ProcessSale` que has proporcionado, ahora podemos ver claramente cómo se integra todo el sistema. Vamos a repasar cómo funciona cada parte del código y cómo se pasa el objeto `Product`.
//IMPORTANTE QUE SE IMPLEMENTA CALLBACK CON CLOSURE
//LOS CLOSURE SON FUNCIONES ANONIMAS QUE PUEDEN ACCEDER A VARIABLES FUERA DE SU ALCANCE
//POR EJEMPLLO NOS PERMITEN ALMACENAR LAS PROPIEDADES Y DEMAS DE UNA CLASE CONTEXTO Y USARLAS DENTRO DE LA FUNCION ANONIMA
//QUE ESTA MISMA PUEDA ESTAR REGISTRADA DESDE OTRA CLASE, PERO AL USAR CLOSURE YA LAS ALMACENAMOS EN SU CONTEXTO Y SON ACCESIBLES

class Product
{
    public string $name;
    public float $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
}


#### Clase `Totalizer3`

// Esta clase se encarga de acumular el total de precios de los productos y verificar si el total supera un límite.


class Totalizer3
{
    private float $count = 0;
    private float $amt = 0;

    public function warnAmount(int $amt): callable
    {
        $this->amt = $amt; // Establece el límite
        return \Closure::fromCallable([$this, 'processPrice']); // Devuelve un Closure
    }

    private function processPrice(Product $product): void
    {
        $this->count += $product->price; // Acumula el precio del producto
        print " count: {$this->count}\n"; // Muestra el total acumulado

        // Verifica si el total ha superado el límite
        if ($this->count > $this->amt) {
            print " high price reached: {$this->count}\n"; // Mensaje de advertencia
        }
    }
}


#### Clase `ProcessSale`

// Esta clase gestiona las ventas y permite registrar múltiples callbacks que se ejecutan cuando se realiza una venta.


class ProcessSale
{
    private array $callbacks = []; // Inicializa un array para los callbacks

    public function registerCallback(callable $callback): void
    {
        $this->callbacks[] = $callback; // Agrega el callback al array
    }

    public function sale(Product $product): void
    {
        print "{$product->name}: processing \n"; // Muestra el nombre del producto que se está procesando

        // Llama a cada callback registrado, pasando el producto
        foreach ($this->callbacks as $callback) {
            call_user_func($callback, $product); // Ejecuta el callback
        }
    }
}


### Flujo Completo de Ejecución

// Aquí está cómo se unen todas las partes cuando ejecutas el código:

// 1. **Creación de Instancias**:
//    - Instancias de `Totalizer3` y `ProcessSale` se crean.

// 2. **Registro del Callback**:
//    - Cuando llamas a `$totalizer3->warnAmount(8)`, se establece el límite de 8 y se devuelve un Closure que llama a `processPrice`.
//    - Este Closure se registra en la instancia de `ProcessSale` mediante `$processor->registerCallback(...)`.

// 3. **Procesamiento de Ventas**:
//    - Al llamar a `$processor->sale(new Product("shoes", 6));`, se imprime `"shoes: processing"` y luego se itera sobre todos los callbacks registrados.
//    - Por cada callback, se llama a `call_user_func($callback, $product);`, lo que invoca el Closure con el objeto `Product`.

// 4. **Acumulación de Precios**:
//    - Dentro del Closure, `processPrice` se ejecuta, acumulando el precio del producto y verificando si el total supera el límite.


// Uso del código
$totalizer3 = new Totalizer3();
$processor = new ProcessSale();
$processor->registerCallback($totalizer3->warnAmount(8));

$processor->sale(new Product("shoes", 6)); // Primera venta
print "\n"; // Nueva línea
$processor->sale(new Product("coffee", 8)); // Segunda venta


### Salida Esperada

// Al ejecutar el código, deberías ver una salida similar a:


// shoes: processing 
//  count: 6

// coffee: processing 
//  count: 14
//  high price reached: 14


### Resumen

// - **Registro de Callbacks**: `ProcessSale` permite registrar múltiples callbacks que se ejecutan al procesar una venta.
// - **Paso de Producto**: Cuando se llama a `sale`, el objeto `Product` se pasa a cada callback registrado, permitiendo que `Totalizer3` actualice su contador y verifique si se ha superado el límite.
// - **Ejecución Fluida**: El uso de Closures permite que `processPrice` acceda al contexto de `Totalizer3`, manteniendo su estado.

// Si tienes más preguntas o necesitas más detalles sobre alguna parte, ¡no dudes en preguntar!