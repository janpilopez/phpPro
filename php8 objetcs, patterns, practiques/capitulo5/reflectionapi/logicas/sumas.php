<?php
// Definir la cadena de entrada
$input = "11  35\n45 64  \n9   15"; // Aquí puedes cambiar los valores según necesites

// Dividir la entrada en líneas
$lines = explode("\n", $input);

// Procesar cada línea
foreach ($lines as $line) {
    // Separar los números
    // list($x, $y) = explode(' ', trim($line));
        // Separar los números, ignorando múltiples espacios
    list($x,$y) = preg_split('/\s+/', trim($line));
    // $result = preg_split($pattern, $input); VALIDO TAMBIEN

    // Calcular la suma mínima
    $minX = str_replace('6', '5', $x);
    $minY = str_replace('6', '5', $y);
    $minSum = intval($minX) + intval($minY);

    // Calcular la suma máxima
    $maxX = str_replace('5', '6', $x);
    $maxY = str_replace('5', '6', $y);
    $maxSum = intval($maxX) + intval($maxY);

    // Imprimir los resultados
    echo $minSum . " " . $maxSum . "\n";
}



// NOTA: SI ESPECIFICAMOS $limitephp
// $input = "uno dos tres cuatro cinco";
// Sin límite (-1 o omitido):
// $result = preg_split('/\s+/', $input);
// $result será ["uno", "dos", "tres", "cuatro", "cinco"]
// Con límite de 3:
// $result = preg_split('/\s+/', $input, 3);
// $result será ["uno", "dos", "tres cuatro cinco"]