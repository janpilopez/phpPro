<?php
//PODEMOS USAR UNA VARIABLE GLOBAL DENTRO DE UNA FUNCION, Y NO ES NECESARIO PASARLE COMO PARAMETRO
$nombre = "Juan";
$email = "jean1xd@gmail.com";

function saludar(){
    global $nombre;//sale de la funcion y busca la variable nombre 
    echo "Hola $nombre, bienvenido";
}

saludar();
