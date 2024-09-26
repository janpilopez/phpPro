<?php
function a($string)
{
    echo "Test case".$string;
    function b()
    {
        echo "Messing with the code here";
    }

}
a("forty five");
b();
//en resumen no se puede ejecutar funciones anidad, si hay respuesta, pero no es lo correct
//  ya que b solo puede ser llamada desde a