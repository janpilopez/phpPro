<?php
$cursos = array("javascript", "php", "laravel"); //existen en todas las veriones de php
$curso = ["javascript", "php", "laravel"]; //apartir de php 7.0
//
//agregar elementos
//1. $cursos[] = "python";
//2. array_push($cursos, "python", "css);//permite multiples elementos
//3. $cursos[3] = "python";

//MATRIZ ES UN ARRAY DE ARRAYS GUARDAN MAS VALORES O ELEMENTOS
///PARA ACCEDER SE USA EL INDICE DE LA MATRIZ Y EL INDICE DEL ARRAY [0][0]

//ARAY POP SACA EL ULTIMO ELEMENTO DEL ARRAY
//array_pop($cursos);

//array_shift($cursos); //saca el primer elemento del array

//array_unshift($cursos, "python");//agrega un elemento al principio del array

//array_splice($cursos, 3, 0, "python");//agrega un elemento en la posicion especificada 3
//desde el tercer elemento  elimina los dos iguiente

in_array("php", $cursos); //busca un elemento en el array, devuelve true o false

isset($cursos[10]); //verifica si existe el indice en el array

#ORDERNAR LOS ARRAY
sort($cursos); //ordena los elementos de un array de menor a mayor O ALFABETICAMENTE
rsort($cursos); //ordena los elementos de un array de mayor a menor O ALFABETICAMENTE, al reves
//cuando se hace un sort  o rsort de pierden los indices de un array asociativo


ksort($cursos); //ordena los elementos de un array asociativo por clave-key
krsort($cursos); //ordena los elementos de un array asociativo por clave-key al reves mayor a menor

implode(", ", $cursos); //convierte un array en un string, separado por coma, o el caracter que definamos
explode(", ", "MEXICO,ECUADOR,PAISES,COLOMBIA"); //convierte un string en un array, separado por coma(o el caracter indicado), o el caracter que definamos
 
array_rand($cursos); //devuelve un indice aleatorio de un array