<?php
date_default_timezone_set('America/Argentina/Buenos_Aires'); //establecemos la zona horaria, si no toma la del servidor greengwich
$ahora = time(); //devuelve formato expo o unix, desde 1970 a la fecha actual en numeros (segundos)
$ahora_formateado = date("d-m-Y H:i:s", $ahora); //establecemos el formato y le pasamos el formato unix, por defecto si o hahy le pase el tiempo actual
echo date("d \d\e m e T H:i:s", $ahora);	//con barra invertida ignoramos la cadena de texto para mostrarla y no la procesa
echo date('d \d\e m e T H:i:s', $ahora);	//con barra invertida ignoramos la cadena de texto para mostrarla y no la procesa

$ahora = time();
$limite = strtotime("+10 day");
echo date("d-m-Y H:i:s", $limite);

