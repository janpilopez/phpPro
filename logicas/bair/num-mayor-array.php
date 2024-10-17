<?php
// <!-- Un restaurante ha lanzado una aplicación para la entrega de comida a domicilio y se han enviado 'n' opiniones sobre varios de los platos de su menú. Los usuarios han enviado una calificación de 5 (1 es la peor y 5 la mejor). El gerente quiere saber cuál es el plato más querido del restaurante.

// Descubra el plato con la calificación promedio más alta.
// Si dos platos tienen la misma calificación, devuelva el plato con el ID más pequeño.

// Complete la función solution() proporcionada en el editor. La función toma los siguientes 2 parámetros y devuelve la solución:
// n. representa la cantidad de críticas. Este parámetro es para fines internos, no lo elimine de la función.
// calificaciones, representa la revisión de cada plato.

// El formato de entrada para pruebas personalizadas.
// Use este formato de entrada si está probando contra una entrada personalizada o escribiendo código en un lenguaje donde no proporcionamos código de bolierplate.
// La primera línea contiene n, que denota la cantidad de críticas.
// Luego, la línea n contiene dos números enteros cada una, la identificación y la calificación de la revisión número n.

// Formato de salida. Imprima un solo entero que represente el identificador del plato con la calificación más alta. -->
function solution($n, $ratings) {
    // Decodificar la cadena de entrada a un array de arrays
    $ratings = json_decode($ratings, true);
    
    // Array para almacenar la suma de calificaciones y el conteo por plato
    $platos = [];

    // Recoger las calificaciones
    foreach ($ratings as $rating) {
        $id_plato = $rating[0];
        $puntuacion = $rating[1];
        
        if (!isset($platos[$id_plato])) {//creo el plata con puntuacion inicial y su id si no esta creado
            $platos[$id_plato] = [0, 0]; // [suma_calificaciones, conteo]
        }
        $platos[$id_plato][0] += $puntuacion; // Sumar calificación
        $platos[$id_plato][1] += 1; // Contar calificación// cantidad de veces que se ha calificado
    }

    // Variables para encontrar el plato más querido
    $mejor_plato = null;//guarda el id del mejor plato
    $mejor_promedio = -1;//se inicia en menos uno porque puede que algun plato tenga una calificacion de 0

    // Calcular el promedio y determinar el mejor plato
    foreach ($platos as $id_plato => $datos) {
        $suma = $datos[0];//puntuacion
        $conteo = $datos[1];//cantidad de veces que se ha calificado
        $promedio = $suma / $conteo;
        
        // Comparar el promedio y el id del plato
        if ($promedio > $mejor_promedio || ($promedio == $mejor_promedio && ($mejor_plato === null || $id_plato < $mejor_plato  )  )    ) {
            $mejor_promedio = $promedio;
            $mejor_plato = $id_plato;
        }
    }

    return $mejor_plato;
}

// Ejemplo de uso
$n = 5;
$ratings = '[[954545,5], [456456,4], [45646, 5]]';
echo solution($n, $ratings);  // Debería devolver el ID del plato más querido
