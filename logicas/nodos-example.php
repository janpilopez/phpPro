<?php


class TreeNode
{
    public $value;//valor del nodo o nombre
    public $left;//hijo izquierdo
    public $right;//hijo derecho
    public $father;//padre
    public $depth;//profundidad

    public function __construct($value)
    {
        $this->value = $value;//valor del nodo o nombre
        $this->left = null;
        $this->right = null;
        $this->father = null;
        $this->depth = 0;//prfundidad
    }
}

class BinaryTree
{
    private $root;
    private $nodesByDepth = [];//guardamos los nodos por produndidad

    public function __construct($rootValue)
    {
        $this->root = new TreeNode($rootValue);
        $this->nodesByDepth[0] = [$this->root]; // Almacenar la raíz a profundidad 0
    }
                            //5             10          L (1 o 0)
    public function insert($parentValue, $childValue, $isLeft)
    {
        $parentNode = $this->findNode($this->root, $parentValue);
        if ($parentNode) {
            $childNode = new TreeNode($childValue);
            if ($isLeft) {
                $parentNode->left = $childNode;
            } else {
                $parentNode->right = $childNode;
            }
            $childNode->depth = $parentNode->depth + 1;
            $childNode->father = $parentNode->value;
            //los guardamos en un array por profundidad
            $this->nodesByDepth[$childNode->depth][] = $childNode;
        }else{
            echo "No se encontro el nodo padre".$parentValue.$isLeft.$childValue;
        }
    }

    //SIRVE PARA BUSCAR UN NODO EN EL ARBOL, recibe como parametro nodo matriz y el nodo(valor) a buscar
    //para definir si es el nodo padre encontrado
    private function findNode($node, $value)
    {
        if ($node === null) return null;
        if ($node->value === $value) return $node;

        //buscamos por lado izquierdo con funcion recursiva
        $left = $this->findNode($node->left, $value);
        if ($left !== null) return $left;

        //buscamos por lado derecho con funcion recursiva       
        return $this->findNode($node->right, $value);
    }

    public function countNodesWithoutPrimos()
    {

        $nodosNivel = $this->nodesByDepth;
        //ya no es necesario ya lo guardamos en un array por profundidad al momento de la creacion
        // foreach ($this->nodesByDepth as $key => $nodes) {
        //     $nodosNivel[$nodes['depth']][$key] = $nodes;
            
        // };
        // echo(json_encode($nodosNivel));
        $nodosNoPrimos = [];
        $count = 0;
        foreach ($nodosNivel as $key => $nodos){
            if (    count($nodos) > 1   ) {
                foreach ($nodos as $keyA => $nodoA) {
                    foreach ($nodos as $keyB => $nodoB) {
                        if ($nodoA->father != $nodoB->father) {
                            //verificar existencia de un nodo en un array
                            echo "\ncomparar: ".$nodoA->value.'-'.$nodoB->value;
                            if (!in_array($nodoA->value.'-'.$nodoB->value, $nodosNoPrimos)
                                && !in_array($nodoB->value.'-'.$nodoA->value, $nodosNoPrimos)) {
                                $combinacion = [$nodoA->value, $nodoB->value];
                                sort($combinacion);
                                // ordenar con un operador de navegacion espacial, EN ESTE CASO NO APLICA PORQUE DENTRO DE CADA ARRAY EXISTE OTRO ARRAY
                                // usort($combinacion, function($a, $b) {
                                //     return $a['value'] <=> $b['value']; // Ordenar de menor a mayor
                                //     // return $b['edad'] <=> $a['edad']; // ReversaOrdenar de mayor a menor
                                // });
                                $nodosNoPrimos[] = $combinacion[0].'-'.$combinacion[1];
                            }
                        }
                    }
                }
            }else{

            }
        }
        echo "\nNodos sin primos: ".json_encode($nodosNoPrimos);
        return count($nodosNoPrimos);
    }
}

// Entrada definida como cadena, no olvidar aumentar el numero de nodos si ampliamos la expresion
$inputString = "8\nROOT 12\n12 R 3\n12 L 6\n3 R 5\n3 L 4\n2 R 17\n17 L 13\n6 R 10";
                //0.    7
                //1.    1\n1L2
                //2.    \n1R3
                //3.    \n2L4
                //4.    \n2R5
                //5.    \n3L6
                //6.    \n3R7";

                //SEPARAMOS POR SALTOS DE LINEA Y QUITAMOS ESPACIOS EN BLANCO DE LOS EXTREMOS DE CADA LINEA
$input = explode("\n", trim($inputString));

$N = (int)$input[0];

preg_match('/(\d+)/', $input[1], $rootValue);
echo "Valor de la raiz: ".json_encode($rootValue);
$tree = new BinaryTree($rootValue[1]);

//EMPEZAMOS DESDE POSICION 2 PORQUE LAS LINEAS ANTERIORES DE LA EXPRESION YA ESTAN ASIGNADAS
//POR DEFECTO SIEMPRE VENDRAN MINIMO 2 LINEAS POR ROOT Y NUMERO TOTAL DE NODOS
for ($i = 2; $i < $N + 1; $i++) {
    $line = trim($input[$i]);
    //seleccionamos o deglosamos la cadena en 3 partes separados por L O R
    if (preg_match('/^(.*)(L|R)(.*)$/', $line, $matches)) {
        echo "\nLinea: ".json_encode($matches);
        //EL [0] ES LA COINCIDENCIA COMPLETA, LAS DEMAS SON DESGLOSADAS
        $parent = trim($matches[1]);//eliminamos los espacios de las coincidencias
        $child = trim($matches[3]);
        $isLeft = $matches[2] === 'L';
        $tree->insert($parent, $child, $isLeft);
    }
}

// Contar nodos sin primos y mostrar resultado
$result = $tree->countNodesWithoutPrimos();
echo $result . PHP_EOL;








// Claro, aquí tienes una explicación detallada del código PHP que implementa un árbol binario y cuenta los nodos sin primos:
// 1. Definición de la Clase TreeNode

// php

// class TreeNode {
//     public $value;
//     public $left;
//     public $right;
//     public $depth;

//     public function __construct($value) {
//         $this->value = $value;
//         $this->left = null;
//         $this->right = null;
//         $this->depth = 0;
//     }
// }

//     Propiedades:
//         $value: Almacena el valor del nodo.
//         $left: Referencia al hijo izquierdo del nodo (inicialmente null).
//         $right: Referencia al hijo derecho del nodo (inicialmente null).
//         $depth: Profundidad del nodo en el árbol (inicialmente 0).

//     Constructor: Inicializa un nuevo nodo con un valor dado y establece sus hijos como null y su profundidad en 0.

// 2. Definición de la Clase BinaryTree

// php

// class BinaryTree {
//     private $root;
//     private $nodesByDepth = [];

//     Propiedades:
//         $root: Referencia al nodo raíz del árbol.
//         $nodesByDepth: Un arreglo que almacena nodos organizados por su profundidad en el árbol.

// 3. Constructor de BinaryTree

// php

// public function __construct($rootValue) {
//     $this->root = new TreeNode($rootValue);
//     $this->nodesByDepth[0] = [ $this->root ]; // Almacenar la raíz a profundidad 0
// }

//     Crea un nodo raíz con el valor proporcionado y lo agrega a la lista de nodos por profundidad en el índice 0.

// 4. Método insert

// php

// public function insert($parentValue, $childValue, $isLeft) {
//     $parentNode = $this->findNode($this->root, $parentValue);
//     if ($parentNode) {
//         $childNode = new TreeNode($childValue);
//         if ($isLeft) {
//             $parentNode->left = $childNode;
//         } else {
//             $parentNode->right = $childNode;
//         }
//         $childNode->depth = $parentNode->depth + 1;
//         $this->nodesByDepth[$childNode->depth][] = $childNode;
//     }
// }

//     Objetivo: Insertar un nuevo nodo como hijo izquierdo o derecho de un nodo padre.
//     Busca el nodo padre usando el método findNode.
//     Crea un nuevo nodo hijo y lo asigna al lado izquierdo o derecho del padre según el valor de $isLeft.
//     Actualiza la profundidad del nuevo nodo y lo añade a la lista de nodos por profundidad.

// 5. Método findNode

// php

// private function findNode($node, $value) {
//     if ($node === null) return null;
//     if ($node->value === $value) return $node;

//     $left = $this->findNode($node->left, $value);
//     if ($left !== null) return $left;

//     return $this->findNode($node->right, $value);
// }

//     Objetivo: Buscar un nodo en el árbol que tenga un valor específico.
//     Utiliza recursión para buscar primero en el subárbol izquierdo y luego en el derecho.
//     Retorna el nodo encontrado o null si no se encuentra.

// 6. Método countNodesWithoutPrimos

// php

// public function countNodesWithoutPrimos() {
//     $count = 0;
//     foreach ($this->nodesByDepth as $depth => $nodes) {
//         if (count($nodes) > 1) { // Hay primos en esta profundidad
//             continue;
//         }
//         $count += count($nodes); // Todos los nodos a esta profundidad son sin primos
//     }
//     return $count;
// }

//     Objetivo: Contar los nodos que no tienen primos.
//     Recorre los nodos agrupados por profundidad.
//     Si hay más de un nodo en esa profundidad, significa que hay nodos primos, así que se ignoran.
//     Si solo hay un nodo, se cuenta como un nodo sin primos.

// 7. Entrada Definida como Cadena

// php

// $inputString = "7\n1\n1L2\n1R3\n2L4\n2R5\n3L6\n3R7";
// $input = explode("\n", trim($inputString));

//     La cadena $inputString representa la entrada del árbol.
//     explode se utiliza para dividir la cadena en líneas y almacenarlas en un arreglo $input.

// 8. Construcción del Árbol

// php

// $N = (int)$input[0];
// $rootValue = trim($input[1]);
// $tree = new BinaryTree($rootValue);

// for ($i = 2; $i < $N + 1; $i++) {
//     $line = trim($input[$i]);
//     if (preg_match('/^(.*)(L|R)(.*)$/', $line, $matches)) {
//         $parent = trim($matches[1]);
//         $child = trim($matches[3]);
//         $isLeft = $matches[2] === 'L';
//         $tree->insert($parent, $child, $isLeft);
//     }
// }

//     Se obtiene el número total de nodos y el valor de la raíz.
//     Se crea un nuevo árbol binario.
//     Luego se recorren las líneas de entrada restantes y se insertan los nodos en el árbol usando el método insert, determinando si son hijos izquierdos o derechos mediante expresiones regulares.

// 9. Conteo de Nodos Sin Primos y Mostrar Resultado

// php

// $result = $tree->countNodesWithoutPrimos();
// echo $result . PHP_EOL;

//     Se llama al método para contar los nodos sin primos y se imprime el resultado.

// Resumen

// Este código implementa un árbol binario y proporciona la funcionalidad para contar nodos que no tienen primos, siguiendo un enfoque claro y estructurado. Puedes modificar la cadena de entrada para probar diferentes configuraciones del árbol y verificar el comportamiento del programa.







// Enunciado del Problema

// Se tiene un árbol binario, donde se define como "primo" de un nodo a cualquier otro nodo que esté a la misma profundidad, pero que no sea su hermano (nodo que comparte el mismo padre). Se requiere contar la cantidad de nodos que no tienen primos.
// Entrada

// La entrada se compone de las siguientes partes:

//     Número de nodos (N): Un entero que indica el número total de nodos en el árbol (N > 0; N <= 1000).
//     Raíz (ROOT R): La segunda línea contiene la palabra "ROOT" seguida del valor del nodo raíz del árbol.
//     Relaciones de nodos: Cada línea subsiguiente (N-1 líneas) describe la relación entre un nodo padre y su hijo, especificando si es un hijo izquierdo o derecho. La sintaxis es la siguiente:
//         P L C: El nodo C es hijo izquierdo de P.
//         P R C: El nodo C es hijo derecho de P.

// Ejemplo de Entrada

// Vamos a usar el siguiente ejemplo de entrada para ilustrar el problema:

// 7
// ROOT 12
// 12 R 3
// 13 L 3
// 3 R 6
// 3 L 4
// 2 R 17
// 17 L 13

// Desglose de la Entrada

//     Número de nodos (7): Indica que el árbol tendrá 7 nodos en total.
//     Raíz (ROOT 12): Aquí, 12 es el valor del nodo raíz, que se llama ROOT.
//     Relaciones:
//         12 R 3: El nodo 3 es el hijo derecho de 12.
//         13 L 3: El nodo 3 es el hijo izquierdo de 13.
//         3 R 6: El nodo 6 es el hijo derecho de 3.
//         3 L 4: El nodo 4 es el hijo izquierdo de 3.
//         2 R 17: El nodo 17 es el hijo derecho de 2.
//         17 L 13: El nodo 13 es el hijo izquierdo de 17.

// Estructura del Árbol

// Basado en las relaciones anteriores, el árbol se puede visualizar de la siguiente manera:

// markdown

//           ROOT
//             |
//             12
//            /  \
//           3    2
//          / \     \
//         4   6    17
//                  /
//                 13

// Análisis por Niveles

//     Profundidad 0:
//         ROOT (1 nodo) — no tiene primos.

//     Profundidad 1:
//         12 (1 nodo) — no tiene primos.

//     Profundidad 2:
//         3, 2 (2 nodos) — ambos tienen primos. (3 tiene primos en 4 y 6), así que no cuentan.

//     Profundidad 3:
//         4, 6, 17 (3 nodos) — 4 y 6 no tienen primos, pero 17 tiene un primo (13).

//     Profundidad 4:
//         13 (1 nodo) — no tiene primos.

// Conteo Final de Nodos sin Primos

// Los nodos que no tienen primos son:

//     ROOT (profundidad 0)
//     12 (profundidad 1)
//     4 (profundidad 3)
//     6 (profundidad 3)
//     13 (profundidad 4)

// Total: 5 nodos.
// Código PHP

// Aquí tienes el código PHP que construye el árbol y cuenta los nodos que no tienen primos.

// php

// <?php

// // Clase que representa un nodo del árbol
// class Node {
//     public $value;  // Valor del nodo
//     public $left;   // Hijo izquierdo
//     public $right;  // Hijo derecho

//     public function __construct($value) {
//         $this->value = $value;
//         $this->left = null;
//         $this->right = null;
//     }
// }

// // Clase que representa el árbol binario
// class BinaryTree {
//     public $root;  // Raíz del árbol

//     public function __construct($value) {
//         $this->root = new Node($value);
//     }

//     // Inserta un nodo en el árbol
//     public function insert($parentValue, $childValue, $isLeft) {
//         $parentNode = $this->find($this->root, $parentValue);
//         if ($parentNode) {
//             if ($isLeft) {
//                 $parentNode->left = new Node($childValue);
//             } else {
//                 $parentNode->right = new Node($childValue);
//             }
//         }
//     }

//     // Encuentra un nodo en el árbol
//     public function find($node, $value) {
//         if ($node === null) return null;
//         if ($node->value == $value) return $node;

//         // Buscamos en el subárbol izquierdo
//         $foundNode = $this->find($node->left, $value);
//         if ($foundNode) return $foundNode;

//         // Buscamos en el subárbol derecho
//         return $this->find($node->right, $value);
//     }

//     // Cuenta los nodos sin primos
//     public function countNodesWithoutPrimos() {
//         $depthMap = [];
//         $this->populateDepthMap($this->root, 0, $depthMap);

//         $count = 0;
//         // Contamos cuántos nodos hay sin primos
//         foreach ($depthMap as $depth => $nodes) {
//             if (count($nodes) == 1) { // Si solo hay un nodo en esta profundidad
//                 $count += 1; // Incrementa el contador
//             }
//         }

//         return $count; // Devuelve el total de nodos sin primos
//     }

//     // Rellena el mapa de profundidades
//     private function populateDepthMap($node, $depth, &$depthMap) {
//         if ($node === null) return;

//         // Agregamos el nodo al mapa según su profundidad
//         if (!isset($depthMap[$depth])) {
//             $depthMap[$depth] = [];
//         }
//         $depthMap[$depth][] = $node->value;

//         // Llamadas recursivas para los hijos
//         $this->populateDepthMap($node->left, $depth + 1, $depthMap);
//         $this->populateDepthMap($node->right, $depth + 1, $depthMap);
//     }
// }

// // Lectura de entrada
// $input = "7\nROOT 12\n12 R 3\n13 L 3\n3 R 6\n3 L 4\n2 R 17\n17 L 13";
// $lines = explode("\n", trim($input));
// $n = (int)$lines[0]; // Número total de nodos
// $rootValue = trim($lines[1]); // Valor de la raíz

// $tree = new BinaryTree($rootValue); // Crear el árbol

// // Procesar las relaciones padre-hijo
// for ($i = 2; $i < count($lines); $i++) {
//     $line = trim($lines[$i]);
//     if (empty($line)) continue;

//     $parts = explode(' ', $line);
//     $parentValue = $parts[0]; // Valor del nodo padre
//     $childValue = $parts[1]; // Valor del nodo hijo
//     $isLeft = $parts[2] == 'L'; // 'L' para hijo izquierdo, 'R' para hijo derecho

//     // Insertar el nodo en el árbol
//     $tree->insert($parentValue, $childValue, $isLeft);
// }

// // Contar nodos sin primos y mostrar el resultado
// $result = $tree->countNodesWithoutPrimos();
// echo $result . "\n"; // Imprime el número total de nodos sin primos

// 


// Explicación del Código

// Clase Node:
// Representa un nodo en el árbol.
// Tiene atributos para el valor del nodo, su hijo izquierdo y su hijo derecho.

// Clase BinaryTree:
// Contiene el nodo raíz y métodos para insertar nodos, buscar nodos y contar nodos sin primos.
// Método insert: Inserta un nuevo nodo como hijo izquierdo o derecho de un nodo padre.
// Método find: Busca un nodo en el árbol.
// Método countNodesWithoutPrimos: Cuenta cuántos nodos no tienen primos al agrupar nodos por profundidad y contar aquellos que están solos.
// Método populateDepthMap: Llena un mapa que agrupa los nodos según su profundidad.

// Lectura de entrada: Procesa la entrada en formato de texto y construye el árbol de acuerdo a las relaciones dadas.

// Resultado: Al ejecutar el script, se imprime el número total de nodos que no tienen primos.

// Resultado Final

// Al ejecutar el código con la entrada