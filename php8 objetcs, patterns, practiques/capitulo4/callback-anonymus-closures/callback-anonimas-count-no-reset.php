<?php
// Exactamente, si llamas a la función warnAmount varias veces, el valor de $count no se reiniciará a 0 en cada llamada. Vamos a aclarar esto con un ejemplo más detallado.
// Comportamiento de warnAmount

    // Definición de warnAmount:
        // Cuando llamas a warnAmount, se define una función anónima que captura el valor de $amt y una referencia a $count.

    // Estado Persistente:
        // Dado que $count se pasa por referencia (gracias al &), el estado de $count persiste entre las invocaciones de la función anónima.

// Ejemplo Detallado

// Aquí tienes un ejemplo completo:
// php

class ProductManager
{
    public static function warnAmount($amt): callable
    {
        $count = 0; // Inicializa el contador
        return function ($product) use ($amt, &$count) {
            $count++; // Incrementa el contador
            return "Processed $product with amount $amt. Count: $count";
        };
    }
}
// Crear la función de advertencia
$warningFunc = ProductManager::warnAmount(100);

// Llamar a la función varias veces
echo $warningFunc("Product A"); // Count: 1
echo "\n";
echo $warningFunc("Product B"); // Count: 2
echo "\n";
echo $warningFunc("Product C"); // Count: 3
//como ya esta instanciada no se reinicia el contador, la explicacion a detalle mas abajo leer todo
// Salida Esperada

// Cuando ejecutas el código anterior, la salida será:

// Processed Product A with amount 100. Count: 1
// Processed Product B with amount 100. Count: 2
// Processed Product C with amount 100. Count: 3

// Explicación

//     Primera Llamada: Cuando llamas a $warningFunc("Product A"), $count se incrementa de 0 a 1.
//     Segunda Llamada: Al llamar a $warningFunc("Product B"), $count se incrementa de 1 a 2.
//     Tercera Llamada: En la llamada a $warningFunc("Product C"), $count se incrementa de 2 a 3.

// Resumen

//     Persistencia del Estado: Debido al uso de &, $count mantiene su valor entre las invocaciones de la función anónima, y no se reinicia en cada llamada.
    // Uso de use: La palabra clave use, junto con &, permite que la función anónima acceda a variables del ámbito exterior y pueda modificarlas.
    echo "\n------------------------------------------";

// Crear dos funciones de advertencia distintas
$warningFunc1 = ProductManager::warnAmount(100);
echo "\n";

$warningFunc2 = ProductManager::warnAmount(200);
echo "\n";

// Llamar a la primera función varias veces
echo $warningFunc1("Product A"); // Count: 1
echo "\n";
echo $warningFunc1("Product B"); // Count: 2
echo "\n";

// Llamar a la segunda función
echo $warningFunc2("Product C"); // Count: 1
echo "\n";

echo $warningFunc2("Product D"); // Count: 2