<?php
for ($i = 0; $i < 3; $i++) {
    for($j = $i; $j>0; $j--){
        echo " ";
        for($k = $j; $k<3; $k++)
            echo "*";
    }
    echo "\n";
}
// error de indentacion, no respuesta valida
//pero segun el debug sale
// * * *
//   * * *
//     * * *