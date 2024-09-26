<?php
function multi($num)
{
    if ($num == 3) {
        echo '3';
    }
    if ($num == 7) {
        echo '7';
    }
    if ($num == 8) {
        echo '8';
    }
    if ($num == 19) {
        echo '19';
    }
}

$can = stripos("I love php, I love php too!", "PHP");
multi($can);

$findme    = 'a';
$mystring1 = 'xyz';
$mystring2 = 'BCA';

$pos1 = stripos($mystring1, $findme);
$pos2 = stripos($mystring2, $findme);

// No, 'a' sin duda no está en 'xyz'
if ($pos1 === false) {
    echo "El string '$findme' no se encontró en el strng '$mystring1'";
}

// Observe el uso de ===.  Usar solamente == no funcionará como se espera
// debido a que la posición de 'a' es el 0º (primer) caracter.
if ($pos2 !== false) {
    echo "Se encontró '$findme' en '$mystring2' en la posición $pos2";
}