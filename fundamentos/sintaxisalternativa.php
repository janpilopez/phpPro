<?php
//en laravel se maneja con @ blade, pero normalmente es asi 
//otra opcion es si trabajamos con llave y corchete todo el condicional debe trabajar con llave
if(true):
    
    echo "Hola mundo";
else:
    echo "Adios mundo";
endif;

if (true) {
    echo "hola";
}else{
    echo "adios";
}
//do while es la unica estructura que no acepta la estructura alternativa, porque debe cerrar con while
switch($variable):
    case 1:
        echo "Caso 1";
        break;
    case 2:
        echo "Caso 2";
        break;
    default:
        echo "Caso por defecto";
endswitch;