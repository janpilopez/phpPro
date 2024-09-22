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
require_once("tasks/{$classname}.php"); //el ejemplo correigo esta en la clase task php
$classname = "tasks\\$classname";
$myObj = new $classname();
$myObj->doSpeak();
// Este script puede adquirir la cadena que asigno a $classname desde un archivo de configuración
// o comparando una solicitud web con el contenido de un directorio. Luego, puede usar
// la cadena para cargar un archivo de clase e instanciar un objeto. Observe que he construido una
// calificación de espacio de nombres en este fragmento.
// Por lo general, haría algo como esto cuando desea que su sistema pueda
// ejecutar complementos creados por el usuario. Antes de hacer algo tan riesgoso como eso en un proyecto real,
// debería verificar que la clase exista, que tenga los métodos que espera, etc.

// Esto no quiere decir que los complementos no sean una buena idea. Permitir que desarrolladores externos
// mejoren un sistema central puede ofrecer una gran flexibilidad. Para garantizar una mayor seguridad, es posible
#OJO###### que admita un directorio para complementos, pero que exija que los archivos de código sean instalados
// por un administrador del sistema, ya sea directamente o desde un entorno de
// administración protegido por contraseña. El administrador comprobaría personalmente
// el código del complemento antes de la instalación o buscaría complementos en un repositorio
// con buena reputación. Esta es la forma en que la popular plataforma de blogs, WordPress, maneja los complementos.


// Looking for Classes
// The class_exists() function accepts a string representing the class to check for and
// returns a Boolean true value if the class exists and false otherwise.
// Using this function, I can make the previous fragment a little safer:
$base = __DIR__;
$classname = "Task";
$path = "{$base}/tasks/{$classname}.php";
if (! file_exists($path)) {
    throw new \Exception("No such file as {$path}");
}
require_once($path);
$qclassname = "tasks\\$classname";
if (! class_exists($qclassname)) {
    throw new \Exception("No such class as $qclassname");
}
$myObj = new $qclassname();
$myObj->doSpeak();
// Por supuesto, no puedes estar seguro de que la clase en cuestión no requiera argumentos de constructor. Para ese nivel de seguridad, tendrías que recurrir a la API Reflection, que se trata
// más adelante en este capítulo. Sin embargo, class_exists() te permite comprobar que la clase
// existe antes de trabajar con ella.

//     Sanitización de la Ruta: Puedes eliminar o escapar caracteres potencialmente peligrosos, como .. (que permite navegar hacia arriba en el sistema de archivos) y . (que representa el directorio actual).
//     Validación de la Ruta: Asegúrate de que la ruta proporcionada se ajuste a una lista de rutas permitidas o directorios esperados.



// También puede obtener una matriz de todas las clases definidas en su proceso de script usando la función
// get_declared_classes():

print_r(get_declared_classes());
// Esto mostrará una lista de las clases definidas por el usuario y las clases integradas. Recuerde que solo devuelve las
// clases declaradas en el momento de la llamada a la función. Puede ejecutar require() o require_
// once() más adelante y, de ese modo, aumentar la cantidad de clases en su script.


// Aprender sobre un objeto o una clase
// Como ya sabes, puedes restringir los tipos de objeto de los argumentos de un método utilizando sugerencias de tipo de clase.
// Incluso con esta herramienta, no siempre puedes estar seguro del tipo de un objeto.
// Existen varias herramientas básicas disponibles para comprobar el tipo de un objeto. En primer lugar,
// puedes comprobar la clase de un objeto con la función get_class(). Esta acepta cualquier
// objeto como argumento y devuelve su nombre de clase como una cadena:
$product = self::getProduct();
if (get_class($product) === 'popp\ch05\batch05\CdProduct') {
    print "\$product is a CdProduct object\n";
}
// El operador instanceof funciona con dos operandos: el objeto a probar a la izquierda de
// la palabra clave y el nombre de la clase o interfaz a la derecha. Se resuelve como verdadero si el objeto
// es una instancia del tipo indicado: