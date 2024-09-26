<?php
$a = array("hi", "hello", "bye");
for (; count($a) < 5;) { 
    if (count($a) == 3) {
        print $a;
    }
}
//solucion no salida error
//si no en todo caso sale arrayarrayarrayarray...  y asi infinito en este bucle infinito ya que no tiene condicional