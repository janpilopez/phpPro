<?php
// Tu tarea en este desafío es escribir una función que transforme una duración (dada como un número de segundos) en texto legible.
// La función debe aceptar un entero no negativo, si es cero, solo devuelve "ahora". De lo contrario, la duración se expresa como una combinación de años, días, horas, minutos y segundos.
// Es mucho más fácil de entender con un ejemplo:

// Para segundos = 62, tu función debe devolver "1 minuto y 2 segundos".
// Para segundos = 3662, tu función debe devolver "1 hora, 1 minuto y 2 segundos".

// Para el propósito de este desafío, un año son 365 días y un día son 24 horas.
// Ten en cuenta que los espacios son importantes.

// Reglas detalladas
// La expresión resultante está formada por componentes como 4 segundos, 1 año, etc. En general, un entero positivo y una de las unidades de tiempo válidas, separados por un espacio. La unidad de tiempo se utiliza en plural si el entero es mayor que 1.

// Los componentes se separan con una coma y un espacio (", "), excepto el último componente, que se separa con "y ", tal como se escribiría en inglés.
// Una unidad de tiempo más significativa aparecerá antes que una menos significativa. Por lo tanto, 1 segundo y 1 año no es correcto, pero 1 año y 1 segundo sí lo es.
// Los diferentes componentes tienen diferentes unidades de tiempo. Por lo tanto, no hay una unidad repetida como en 5 segundos y 1 segundo.
// Un componente no aparecerá en absoluto si su valor es cero. Por lo tanto, 1 minuto y 0 segundos no son válidos, pero deberían ser solo 1 minuto.
// Una unidad de tiempo debe usarse "tanto como sea posible". Esto significa que la función no debe devolver 61 segundos, sino 1 minuto y 1 segundo. Formalmente, la duración especificada por un componente no debe ser mayor que cualquier unidad de tiempo válida más significativa.

function solution($seconds)
{
    if ($seconds === 0) {
        return "now";
    }
    //definir unidades de tiempo EN SEGUNDOS
    $timeUnits = [
        'año' => 31536000, // 365 días
        'día' => 86400,    // 24 horas
        'hora' => 3600,    // 60 minutos
        'minuto' => 60,    // 60 segundos
        'segundo' => 1,    // 1 segundo
    ];
    $result = []; // Array para almacenar las partes del resultado
    // Calcular las partes de tiempo, empezamos en el orden especificado
    foreach ($timeUnits as $unit => $value) {
        if ($seconds >= $value) {
                //floor redondea valores hacia abajo
            $count = floor($seconds / $value);//si $seconds es 3662 y estamos en la unidad "hora" (3600), floor(3662 / 3600) devuelve 1, lo que significa que hay 1 hora
            $seconds = $seconds % $value; // Obtener el resto, todo los segundo sobrante por decirlo asi, ahora sera el nuevo valor a comparar
            $result[] = "$count $unit" . ($count > 1 ? "s" : ""); // Pluralizar si es necesario, guardamos en un array cada parte del tiempo
        }
    }

    // Construir la cadena final
    if (count($result) === 1) {
        return $result[0]; // Solo un componente
    } else {
        // array_pop() extrae y devuelve el último elemento del array, acortando el array con un elemento menos. 
        $last = array_pop($result); // Extraer el último componente del array que seria segundos y le anadimos " y" segun la regla
        return implode(", ", $result) . " y " . $last; // Combinar:IMPLODE TRANSFORMA ARRAY EN STRING, SEPARANDO CADA ELEMENTO CON UNA COMA Y UN ESPACIO
        // Condición para Un Componente: Si el tamaño de $result es 1, significa que solo hay un componente (por ejemplo, "1 minuto"). Entonces lo devolvemos directamente.
        // Múltiples Componentes: Si hay más de uno:
        // Usamos array_pop($result) para extraer el último elemento de $result. Esto nos permitirá formar la cadena correctamente (por ejemplo, "1 hora y 2 minutos").
        // Usamos implode(", ", $result) para unir los componentes restantes con una coma y un espacio. Finalmente, añadimos " y " seguido del último componente que extraímos.
    }
}

// Ejemplos de uso
echo solution(62) . "\n";     // "1 minuto y 2 segundos"
echo solution(3662) . "\n";   // "1 hora, 1 minuto y 2 segundos"
echo solution(84123132) . "\n";       // "ahora"
