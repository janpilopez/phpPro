<?php
// Chris and Tasks

// Chris works in company Apple. Since Apple is one of the biggest software MNCs in the world, Chris being a software developer has too much of work load each day. He decided to make a list of works that he needs to do and maintain a checklist so that none of the works is left. He has a long list of tasks that he need to do today. To accomplish his task The needs 'Mi' minutes, and the deadline for this task is 'Di'. He doesn't need to complete a task at a stretch. He can complete a part of it, switch to another task, and then he can switch back.

// Chris realized that it might not be possible for him to complete all the tasks by their deadline. So he decided to do them in such a manner that the maximum amount by which a task's completion time overshoots its deadline is minimized

// Input:

// The first line contains a single integer i.e. 'N' denoting the number of tasks Chris has to accomplish.
// The next 'N' subsequent lines contains 2 space separated integers denoting the values of 'Di'  and 'Mi',

// Output

// Print 'N' number of lines. The ith line contains the value of the maximum amount by which a task's completion time overshoots its deadline, when the first 'i' tasks on his list are scheduled optanally.

// EXAMPLE
// 5
// 2 2
// 1 1
// 4 3
// 10 1
// 2 1

// The first task alone can be completed in 2 minutes, and so you wont overshoot the deadline.

// with the first two tasks, the optimal schedule can be:
// time 1: task 2
// time 2: task 1
// time 3: task 1

// We' ve overshot task 1 by 1 minute, hence returning 1.

// With the first three tasks, the optimal schedule can be:
// time 1: task 2
// time 2: task 1
// time 3: task 3
// time  4: task 1
// time 5: task 3
// time 6: task 3
// task 1 has a deadline 2, and it finishes at time 4. So it exceeds its deadline by 2.
// task 2 has a deadline 1, and it finishes at time 1. So it exeeds its deadline by 0.
// Tasks 3 has a eadline 4, and it finishes at time 6, so it exceeds its deadline by 2.

// this, the maximun time by wich you overshoot a deadline is 2. no schedule can do better than this.
// similar calculation can be done for the case containing 5 tasks.

// then the output will be:
// 0
// 1
// 2
// 2
// 3

// Datos de ejemplo
// $tasks = [
//     [5, 2],
//     [9, 1],
//     [4, 1],
//     [7, 4],
// ];

// Datos de ejemplo
$tasks = [
    [2, 2],
    [1, 1],
    [4, 3],
    [10, 1],
    [2, 1]
];
$n = count($tasks); // Número de tareas
$maxDelays = [];

$currentTime = 0;

for ($i = 0; $i < $n; $i++) {
    // Tareas hasta el índice actual
    $currentTasks = array_slice($tasks, 0, $i + 1);
    // Ordenar las tareas por deadline, osea tiempo de finalizacion maximo, no el que me demoro
    usort($currentTasks, function ($a, $b) {
        return $a[0] <=> $b[0]; // Ordenar por Di
    });
    echo json_encode($currentTasks) . "\n";

    $currentTime = 0;
    $maxDelay = 0;

    // Ejecutar las tareas y calcular el retraso
    foreach ($currentTasks as $task) {//[2,2]
        $currentTime += $task[1]; // Agregar tiempo de tarea [2] ->valor 2
        $delay = max(0, $currentTime - $task[0]); // Calcular el retraso
        $maxDelay = max($maxDelay, $delay); // Actualizar el retraso máximo
        echo "currentTime: ". $currentTime . "\n". "delay: ". $delay . "\n". "maxDelay: ". $maxDelay . "\n";
    }

    // Guardar el retraso máximo para el conjunto actual de tareas
    $maxDelays[] = $maxDelay;
    echo "maxDelays: ". json_encode($maxDelays) . "\n";
}

// Imprimir los resultados
foreach ($maxDelays as $delay) {
    echo $delay . "\n";
}
