<?php
// Propiedades constantes
// Algunas propiedades no se deben cambiar. La respuesta a la vida, el universo y
// todo es 42, y desea que permanezca así. Los indicadores de error y estado a menudo estarán
// codificados en sus clases. Aunque deberían estar disponibles públicamente y de forma estática,
// el código del cliente no debería poder cambiarlos.
// PHP le permite definir propiedades constantes dentro de una clase. Al igual que las constantes globales,
// las constantes de clase no se pueden cambiar una vez que se configuran. Una propiedad constante se declara
// con la palabra clave const. Las constantes no tienen como prefijo un signo de dólar como las propiedades regulares. 
//Por convención, a menudo se nombran utilizando solo caracteres en mayúsculas:

class ShopProduct
{
 public const AVAILABLE = 0;
 public const OUT_OF_STOCK = 1;
}

// Las propiedades constantes solo pueden contener valores primitivos. No se puede asignar un objeto
// a una constante. Al igual que las propiedades estáticas, se accede a las propiedades constantes a través de la clase
// y no de una instancia. Del mismo modo que se define una constante sin un signo de dólar, no se requiere ningún símbolo inicial
// cuando se hace referencia a una:

print ShopProduct::AVAILABLE;
// Note Support for constant visibility modifiers was introduced in PHP 7.1. They work in just the same way as visibility modifiers do for properties.