<?php
//leer contenidos de un archivo
$archivo = 'archivo.txt';
$contenido = file_get_contents($archivo);

//let archivos 

file_exists($archivo); //verifica si el archivo existe


//escribir archivo
file_put_contents($archivo, "Hola mundo\n"); //escribir en un archivo, si no existe lo crea
//recibe ruta del archivo y el contenido a escribir
//tambien se puede agregar FILE_APPEND para agregar contenido al final del archivo, NO SOBREESCRIBIRLO SI NO AGREGAR TODO AL FINAL


#DIFERENCIA ENTRE INCLUDE Y FILE CONTENT
//EL INCLUDE LEE TODAS LAS FUNCIONES VARIABLES Y LAS PROCESAS
//EL fie_get_contents SOLO LEE EL CONTENIDO DEL ARCHIVO EN TEXTO PLANO
//EL FILE GET CONTENTS PERMITE ALMACENAR SU VALOR EN UNA VARIABLE