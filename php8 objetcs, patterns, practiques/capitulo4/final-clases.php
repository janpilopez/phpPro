<?php
// Clases y métodos finales
// La herencia permite una enorme flexibilidad dentro de una jerarquía de clases. Puede anular una
// clase o método de modo que una llamada en un método de cliente logre efectos radicalmente diferentes,
// según la instancia de clase a la que se haya pasado. Sin embargo, a veces una clase o
// método debe permanecer fijo e inmutable. Si ha logrado la funcionalidad definitiva para su
// clase o método y siente que anularlo solo puede dañar
// la perfección final de su trabajo,
// es posible que necesite la palabra clave final.
// final pone fin a la herencia. Una clase final no puede ser subclasificada. De manera menos drástica,
// un método final no puede ser anulado.
// Aquí hay una clase final:

final class CheckoutV1
{
    // ...
}

class IllegalCheckoutV1 extends CheckoutV1
{
    // ...
// PHP Fatal error:  Class IllegalCheckout cannot extend final class Checkout in /home/jean/Documentos/PROGRAMACION/PHP/AprendiendoPHP/phpPro/php8 objetcs, patterns, practiques/capitulo4/final-clases.php on line 19
}

class Checkout
{
    final public function totalize(): void
    {
        // calculate bill
    }
}
class IllegalCheckout extends Checkout
{
    //SE PUEDE AHORA INSTANCIA PERO NO SE PUEDE SOBREESCRIBIR LA CLASE TOTALIZE
    final public function totalize(): void
    {
        // change bill calculation
    }
    // Fatal error: Cannot override final method popp\ch04\batch14\ Checkout::totalize() in /var/popp/src/ch04/batch14/IllegalCheckout.php on
}

// Al declarar una clase o un método como final, limitas esta flexibilidad. Habrá
// ocasiones en las que esto sea deseable, y verás algunas de ellas más adelante en el libro.
// Sin embargo, debes pensarlo detenidamente antes de declarar algo como final. ¿Realmente no hay
// circunstancias en las que la anulación sea útil? Siempre puedes cambiar de opinión
// más adelante, por supuesto, pero esto puede no ser tan fácil si estás distribuyendo una biblioteca para que otros
// la utilicen. Usa final con cuidado.





