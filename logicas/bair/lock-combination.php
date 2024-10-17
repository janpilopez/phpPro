<?php
// Sé un detective.
// Detective, uno de los miembros de nuestro equipo rastreó a nuestro objetivo, Vicent el ladrón, hasta un almacén oculto. Creemos que es allí donde guarda los bienes robados. La entrada a este almacén está protegida por una cerradura de combinación digital. Sin embargo, nuestro informante no está completamente seguro del PIN que vio ingresar a Vicent.

// El teclado tiene la siguiente disposición.

// 1. 2. 3
// 4. 5. 6
// 7. 8. 9
//    0

// El notó el PIN 1357, pero también dijo que es posible que cada uno de los dígitos que vio pudiera ser en realidad otro dígito adyacente (horizontal o verticalmente, pero no diagonalmente). Por ejemplo, en lugar del 1, también podría ser el 2 o el 4, y en lugar del 5, también podría ser el 2, 4, 6, 8.

// También mencionó que conoce este tipo de cerradura. Puedes introducir una cantidad ilimitada de PIN incorrectos, pero nunca bloquearán el sistema ni harán sonar la alarma, por eso podemos probar todas las variaciones posibles.
// Todas posibles en el sentido del propio PIN observado y todas las variaciones considerando los dígitos adyacentes.
// ¿Nos puedes ayudar a encontrar todas esas variaciones? La matriz debería contener todos los PIN posibles ordenados en valor ascendente.
// Detective, contamos contigo.

// NOTAS:
// ENTRADA: PIN en formato de cadena
// SALIDA: MATRIZ de cadena (el propio PIN observado debería estar incluido en la matriz)

// entrada: 8
// salida: [¨0¨, ¨5¨,¨7¨,¨8¨,¨9¨]

// entrada: 11
// salida: [´11´,´12´,´14´,´21´,'22',´24´,´41´,´42´,´44´]
function solution($pin): array
{
    $teclado =  [ 
                    [1, 2, 3], 
                    [4, 5, 6], 
                    [7, 8, 9], 
                    [null, 0, null] 
                ];
    $pinArray = str_split($pin);//string to array
    // echo json_encode($pinArray);
    $combinaciones = [];
    foreach ($pinArray as $key => $onePin) {
        $posx = false;//guarda la posicion en x de la matriz sobre el numero-pin encontrado
        $posy = false; //guarda la posicion en y de la matriz
        foreach ($teclado as $x => $value) {
            // echo json_encode($value);
            $posy = array_search($onePin, $value);

            if ($posy !== false) {
                $posx = $x;
                $combinaciones[] = intval($onePin);
                //buscar combinacion horizontal derecha
                if (isset($teclado[$posx][$posy + 1])) {
                    $combinaciones[] = $teclado[$posx][$posy + 1];
                }
                //buscar combinacion horizontal izquierda
                if (isset($teclado[$posx][$posy - 1])) {
                    $combinaciones[] = $teclado[$posx][$posy - 1];
                }

                //buscar combinacion vertical arriba
                if (isset($teclado[$posx- 1][$posy])){
                    $combinaciones[] = $teclado[$posx -1 ][$posy];
                }
                //buscar combinacion vertical abajo
                if (isset($teclado[$posx + 1][$posy])){
                    $combinaciones[] = $teclado[$posx +1 ][$posy];
                }
                break 1;
            }
            //NO HAY: BUSCAMOS EN EL SIGUIENTE SUBARRAY DEL TECLADO 
    
        }
    }
    // echo json_encode($combinaciones);

    $elementosUnicos = array_values(array_unique($combinaciones));
    

    if (count($pinArray) == 1) {
        sort($elementosUnicos);
    }else{
        //combinar todos los elementos
        $combinaciones = [];
        foreach ($elementosUnicos as $key => $value) {
            foreach ($elementosUnicos as $key2 => $value2) {
                $combinaciones[] = $value . $value2;
            }
        }
        $elementosUnicos = array_values(array_unique($combinaciones));
        sort($elementosUnicos);

    }

    echo json_encode($elementosUnicos);
    return $elementosUnicos;
}

json_encode(solution(11));
