<?php

namespace tasks;

class Task
{
    public function doSpeak()
    {
        print "hello\n";
    }
}
// listing 05.37
$classname = "Task";
require_once("{$classname}.php");
// require_once("tasks/{$classname}.php");
$classname = "tasks\\$classname";
$myObj = new $classname();
$myObj->doSpeak();