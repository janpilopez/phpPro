<!-- Paquetes y espacios de nombres de PHP
Aunque PHP no admite intrínsecamente el concepto de paquete, los desarrolladores han
utilizado tradicionalmente tanto esquemas de nombres como el sistema de archivos para organizar su código en
estructuras similares a paquetes.
Hasta PHP 5.3, los desarrolladores se veían obligados a nombrar sus archivos en un contexto compartido. En
otras palabras, si nombraba una clase ShoppingBasket, estaría disponible instantáneamente
en todo el sistema. Esto causaba dos problemas importantes. El primero, y el más perjudicial, era la
posibilidad de colisiones de nombres. Podría pensar que esto es poco probable. Después de todo, todo lo que tiene que hacer es recordar darle a todas sus clases nombres únicos, ¿verdad? El problema es que todos
dependemos cada vez más del código de biblioteca. Esto es algo bueno, por supuesto, porque promueve la
reutilización del código. Pero supongamos que su proyecto hace esto: -->
<!-- Antes de la introducción de los espacios de nombres, existía una solución convencional
para este problema. La respuesta era anteponer los nombres de los paquetes a los nombres de las clases, de modo que se garantizara que los nombres de las clases fueran únicos: -->
<?php

// before PHP 5.3
require_once __DIR__ . "/../useful/Outputter.php";
class my_Outputter
{
    // output data
}
// listing 05.04
// useful/Outputter.php
class useful_Outputter
{
    //
}



// after


namespace my;

require_once __DIR__ . "/../useful/Outputter.php";
class Outputter
{
    // output data
}

namespace useful;

class Outputter
{
    //
}
//con los namespace se puede tener dos clases con el mismo nombre en el mismo archivo (por ejemplo podemos incluir dos clases Outputter en el mismo archivo)
//no daran errores



// Tenga en cuenta la palabra clave namespace. Como podría esperar, esta palabra clave establece un
// espacio de nombres. Si está utilizando esta función, la declaración del espacio de nombres debe ser la
// primera declaración en su archivo. He creado dos espacios de nombres: my y useful. Sin embargo, normalmente
// querrá tener espacios de nombres más profundos. Comenzará con un identificador de organización o
// proyecto. Luego querrá calificarlo aún más por paquete. PHP le permite
// declarar espacios de nombres anidados. Para hacer esto, simplemente use un carácter de barra invertida para dividir
// cada nivel:
namespace popp\ch05\batch04\util;
class Debug
{
 public static function helloWorld(): void
 {
 print "hello from Debug\n";
 }

 Debug::helloWorld();
}


// listing 05.12
namespace main;
use popp\ch05\batch04\util;

util\Debug::helloWorld();
// The popp\ch05\batch04\util namespace is imported and implicitly aliased to
// util. Notice that I didn’t begin with a leading backslash character. The argument to
// use is searched from root space and not from the current namespace. If I don’t want to
// reference a namespace at all, I can import the Debug class itself:
// listing 05.13
namespace main;
use popp\ch05\batch04\util\Debug;
 Debug::helloWorld();
//  Esta es la convención que se utiliza con más frecuencia. Pero, ¿qué sucedería si ya tuviera una clase Debug en el espacio de nombres que realiza la llamada? Esta es una de esas clases:
namespace popp\ch05\batch04;
class Debug
{
 public static function helloWorld(): void
 {
 print "hello from popp\\ch05\\batch04\\Debug\n";
 }
}
// Así que parece que he cerrado el círculo y he vuelto a las colisione
namespace popp\ch05\batch04;
use popp\ch05\batch04\util\Debug;
use popp\ch05\batch04\Debug as CoreDebug;
CoreDebug::helloWorld();

// Al usar la cláusula as, puedo cambiar el alias de Debug a coreDebug.
// Si estás escribiendo código en un espacio de nombres y quieres acceder a una clase, un rasgo o una interfaz que reside en el espacio raíz (sin espacio de nombres) (por ejemplo, las clases principales de PHP como
// Exception, Error, Closure), puedes simplemente anteponer una barra invertida al nombre. Aquí hay una
// clase declarada en el espacio raíz: