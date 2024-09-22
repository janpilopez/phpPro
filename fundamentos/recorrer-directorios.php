<?php
$directorio = opendir("C:/xampp/htdocs/curso-php");

//recorrer coontenidos/archivos de un directorio y mostrarlos
while ($archivo = readdir($directorio)) {
    if ($archivo == "." || $archivo == "..") {
        continue;
    }
    echo $archivo . "<br>";
}

//RECORRER ARCHIVOS ESPECIFICOS CON FUNCION GLOB 
$archivos = glob("C:/xampp/htdocs/curso-php/*.txt");//devuelve array con los archivos coincidentes

//filesize() //tamanio 
//filemtime() // fecha de creacion
//pathinfo($archivos[0]); //informacion de un archivo, nos trae dirname, basename, extension, filename
dirname($ruta); //nos trae la ruta del archivo
dirname(__FILE__); //nos trae la ruta del archivo actual

//mkdir() //crear directorio
//rmdir()//no debe haber archivos para poder eliminar un directorio