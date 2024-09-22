<?php
//include se utiliza para incluir un archivo en otro archivo,
// lanza manssaje de error y lo que sigue despues del error o codigo se sigue generando
require('constantes.php');
// require se utiliza tambien para incluir un archivo en otro archivo, 
//pero es critico, si no encuentra el archivo, detiene la ejecucion del programa

include_once('constantes.php'); //incluye el archivo una sola vez, osea al incluir el archivo 
//si ya esta incluido no lo vuelve a incluir
require_once 'fecha.php'; //incluye el archivo una sola vez, osea al incluir el archivo, lo mismo que arriba
