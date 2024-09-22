
<?php
//SIEMPRE SE ESCIBREN EN MAYUSCULAS, SON VALORES FIJOS NO PUEDEN SER CAMBIADAS
define('PI', 3.1416);
echo PI; //sin el signo busca una constante a nivel global, no lleva comillas ni signos $

$usuario = "Juan";
echo $usuario;

//var_dump sirve para mostrar toda la anatomia-infomracion de una variable, tipo, valor, longitud
//tambien se puede seabilitar la ruta en config con xdebug.overload_var_dump=1, para que no muestra ruta solo la informacion de la variable
var_dump($usuario);
?>