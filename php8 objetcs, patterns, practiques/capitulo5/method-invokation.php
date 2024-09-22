<?php
// Method Invocation
// You have already encountered an example in which I used a string to invoke a method dynamically:
$product = self::getProduct();
$method = "getTitle"; // define a method name
print $product->$method(); // invoke the method

// PHP también proporciona el método call_user_func() para lograr el mismo fin. call_
// user_func() puede invocar cualquier tipo de función invocable (como un nombre de función o una función anónima).
// Aquí, invoco una función, pasando el nombre de la función en una cadena:
$returnVal = call_user_func("myFunction");
// Para invocar un método, puedo pasar una matriz. El primer elemento de esta matriz debe ser un objeto
// y el segundo debe ser el nombre del método que se invocará:
$returnVal = call_user_func([$myObj, "methodName"]);
// Cualquier otro argumento que se pase a call_user_func() se tratará como
// argumentos de la función o método de destino y se pasarán en el mismo orden, de esta manera:
$product = self::getBookProduct(); // Adquirir un objeto BookProduct
call_user_func([$product, 'setDiscount'], 20);
// Esta llamada dinámica es, por supuesto, equivalente a esto:
$product->setDiscount(20);
// El método call_user_func() no cambiará mucho tu vida porque puedes
// utilizar igualmente una cadena directamente en lugar del nombre del método, de esta manera:
$method = "setDiscount";
$product->$method(20);
// Sin embargo, mucho más impresionante es la función call_user_func_array() relacionada.
// Esta funciona de la misma manera que call_user_func(), en lo que respecta a la selección del método o función de destino. Sin embargo, lo más importante es que acepta cualquier argumento requerido
// por el método de destino como una matriz.
// Nota: tenga cuidado: los argumentos pasados ​​a una función o método mediante call_user_ func() no se pasan por referencia.

#metodos interceptor 
public function __call(string $method, array $args): mixed
{
    if (method_exists($this->thirdpartyShop, $method)) {
        return $this->thirdpartyShop->$method();
    }
}
#TO, CON ESTO PODEMOS PASAR ARGUMENTOS
public function __call(string $method, array $args): mixed
{
    if (method_exists($this->thirdpartyShop, $method)) {
        return call_user_func_array(
        [
            $this->thirdpartyShop,
            $method
        ],
            $args
        );
    }
}