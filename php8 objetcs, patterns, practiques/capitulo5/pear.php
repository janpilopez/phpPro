<?php
// Nota: PEAR significa PHP Extension and Application Repository. Es un
// archivo de paquetes y herramientas mantenido oficialmente que se suman a la funcionalidad de PHP.
// Los paquetes principales de PEAR están incluidos en la distribución de PHP, y se pueden agregar otros
// usando una herramienta de línea de comandos simple. Puede explorar los paquetes de PEAR en
// http://pear.php.net.

// Rutas de inclusión INCLUDE PATHS
// Al organizar los componentes, hay dos perspectivas que debe tener en
// cuenta. He abordado la primera, donde se ubican los archivos y directorios en el sistema de archivos.
// Pero también debe considerar la forma en que los componentes acceden entre sí.
// Hasta ahora, he pasado por alto el tema de las rutas de inclusión en esta sección.
// Cuando incluye un archivo, puede hacer referencia a él utilizando una ruta relativa del directorio de trabajo actual o una ruta absoluta en el sistema de archivos.

// Nota: si bien es importante comprender la forma en que funcionan las rutas de inclusión y
// los problemas relacionados con la solicitud de archivos, también es importante tener en cuenta que muchos
// sistemas modernos ya no dependen de las instrucciones require a nivel de clase. En su lugar,
// utilizan una combinación de carga automática y espacios de nombres

//fixed relationship the requiring and required files
require_once __DIR__ . "/../useful/Outputter.php";

//RUTA RELATIVA
require_once('../../projectlib/business/User.php');

//RUTA ABSOLUTA
require_once('/home/john/projectlib/business/User.php');

// Esto funcionará para una sola instancia, pero es frágil. Al especificar rutas con tanto
// detalle , congela el archivo de la biblioteca en un contexto particular. Siempre que instale
// el proyecto en un nuevo servidor, todas las declaraciones requeridas deberán cambiarse para tener en cuenta
// una nueva ruta de archivo. Esto puede hacer que sea difícil reubicar las bibliotecas y que sea poco práctico compartirlas
// entre proyectos sin hacer copias. En cualquier caso, pierde la idea del paquete en todos
// los directorios adicionales. ¿Es el paquete empresarial o es el paquete projectlib/business?

// Si debe incluir archivos manualmente en su código, el enfoque más ordenado es desacoplar el código que invoca de la biblioteca. Ya ha visto una estructura como esta:
require_once('business/User.php');

// En ejemplos anteriores que utilizaban una ruta como esta, asumimos implícitamente una ruta relativa. business/User.php, en otras palabras, era funcionalmente idéntico a ./business/
// User.php. Pero, ¿qué sucedería si la declaración require anterior pudiera funcionar desde cualquier
// directorio de un sistema? Puede hacerlo con la ruta de inclusión. Esta es una lista de directorios
// que PHP busca cuando intenta solicitar un archivo. Puede agregar a esta lista modificando
// la directiva include_path. include_path generalmente se establece en el archivo de configuración central de PHP, php.ini. Define una lista de directorios separados por dos puntos(:) en sistemas tipo Unix y
// por punto y coma(;) en sistemas Windows:
// include_path = ".:/usr/local/lib/php-libraries";

// If you’re using Apache, you can also set include_path in the server application’s
// configuration file (usually called httpd.conf) or a per-directory Apache configuration
// file (usually called .htaccess) with this syntax:
#php_value include_path value .:/usr/local/lib/php-libraries


#ESTABLECER RUTA DE INLUSION EN PHP.INI O DESDE LA CONIFIGURACION PHP
// Cuando se utiliza una función del sistema de archivos como fopen() o require() con una
// ruta no absoluta que no existe en relación con el directorio de trabajo actual, los
// directorios en la ruta de inclusión se buscan automáticamente, comenzando con el primero
// en la lista (en el caso de fopen(), debe incluir un indicador en su lista de argumentos para habilitar
// esta función). Cuando se encuentra el archivo de destino, la búsqueda finaliza y la función de archivo
// completa su tarea. Por lo tanto, al colocar un directorio de paquetes en un directorio de inclusión, solo necesita hacer referencia a
// paquetes y archivos en sus declaraciones require().
// Es posible que deba agregar un directorio a include_path para poder mantener su
// propio directorio de biblioteca. Para hacer esto, puede editar el archivo php.ini (recuerde que, para el módulo de servidor PHP, deberá reiniciar su servidor para que los cambios surtan efecto).
// Si no tiene los privilegios necesarios para trabajar con el archivo php.ini, puede establecer la ruta de inclusión desde dentro de sus scripts utilizando la función set_include_path().
// set_include_path() acepta una ruta de inclusión (tal como aparecería en php.ini) y
// cambia la configuración de include_path solo para el proceso actual. El archivo php.ini probablemente
// ya define un valor útil para include_path, por lo que en lugar de sobrescribirlo, puede
// acceder a él mediante la función get_include_path() y agregar su propio directorio. Aquí se explica
// cómo puede agregar un directorio a la ruta de inclusión actual:

set_include_path(get_include_path() . PATH_SEPARATOR . "/home/john/
phplib/");
// La constante PATH_SEPARATOR se resolverá en dos puntos en un sistema Unix y en un
// punto y coma en una plataforma Windows. Por lo tanto, por razones de portabilidad, su uso se considera
// la mejor práctica