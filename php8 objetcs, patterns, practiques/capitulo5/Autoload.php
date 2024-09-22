<?php
// Carga automática AUTOLOAD
// Aunque es útil utilizar require_once junto con la ruta de inclusión, muchos
// desarrolladores están eliminando por completo las declaraciones require en un nivel alto y, en cambio, confían en la carga automática.

// Nota: En ediciones anteriores de este libro se hablaba de una función incorporada llamada
// __autoload() que proporcionaba una versión más básica de la funcionalidad que se analiza en
// esta sección. Esta función quedó obsoleta a partir de PHP 7.2.0 y se eliminó en PHP 8.
// spl_autoload_register();
// $writer = new Writer();
// Este comportamiento predeterminado admite espacios de nombres y sustituye los nombres de directorio por cada paquete:
// listing 05.27
// spl_autoload_register();
// $writer = new util\Writer();


// listing 05.29
$basic = function (string $classname) {
    $file = __DIR__ . "/" . "{$classname}.php";
    echo $file;
    if (file_exists($file)) {
        require_once($file);
    }
};
\spl_autoload_register($basic);
$blah = new Blah();
$blah->wave();

// Como no pude crear una instancia de Blah inicialmente, el motor PHP verá que he registrado una función de carga automática con la función spl_autoload_register() y le pasará
// la cadena "Blah". Mi implementación simplemente intenta incluir el archivo Blah.php.
// Esto solo funcionará, por supuesto, si el archivo está en el mismo directorio que el archivo en el que se declaró la función de carga automática. En un ejemplo del mundo real, tendría que combinar
// la configuración de la ruta de inclusión con mi lógica de carga automática (esto es precisamente lo que hace la implementación de carga automática de Composer).

echo "\n";
// If I want to provide old school support, I might automate PEAR package includes:
// // listing 05.30
class util_Blah
{
    public function wave(): void
    {
        print "saying hi from underscore file";
    }
}
// listing 05.31
$underscores = function (string $classname) {
    // Esta línea toma el nombre de la clase ($classname) y reemplaza todos los guiones bajos (_) por el separador de directorios correspondiente al sistema operativo (DIRECTORY_SEPARATOR). Por ejemplo, si $classname es My_Class, el resultado en un sistema UNIX sería My/Class., o windows segun el sistema operativo lo adapta
    $path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
    $path = __DIR__ . "/$path";
    if (file_exists("{$path}.php")) {
        require_once("{$path}.php");
    }
};
\spl_autoload_register($underscores);
$blah = new util_Blah(); //como no lo encuentra le pasara el mismo nombre como cadena al metodo de cargo automatica y registrara la class y la cargara si estuviera en otro archivo dentro del mismo directorio
$blah->wave();


// Resumen:
// Como puede ver, la función de carga automática coincide con los guiones bajos en el $classname suministrado y reemplaza cada uno
//  con el carácter DIRECTORY_SEPARATOR (/ en sistemas Unix). Intento incluir el archivo de clase (util/Blah.php). Si el archivo de clase existe y
//  la clase que contiene tiene el nombre correcto, el objeto debería instanciarse sin un error. Por supuesto, esto requiere que el programador observe una convención de nombres que prohíbe el carácter de guión bajo en un nombre de clase, excepto cuando divide paquetes. ¿Qué sucede con los espacios de nombres? Hemos visto que la funcionalidad de carga automática predeterminada admite espacios de nombres. Pero si anulamos ese valor predeterminado, depende de nosotros proporcionar compatibilidad con espacios de nombres. Esto es solo una cuestión de hacer coincidir y reemplazar caracteres de barra invertida:





namespace util;

class LocalPath
{
    public function wave(): void
    {
        print "hello from " . get_class();
    }
}
// listing 05.33
$namespaces = function (string $path) {
    if (preg_match('/\\\\/', $path)) {
        #La razón por la que hay cuatro barras invertidas (\\\\) en la expresión regular es la siguiente:
        # Escapar la Barra Invertida: En PHP, la barra invertida (\) es un carácter de escape. Por lo tanto, para que PHP reconozca una barra invertida literal dentro de una cadena, debes usar dos barras invertidas (\\).
        # Expresión Regular: Cuando se utiliza la función preg_match(), también se necesita escapar la barra invertida para que la expresión regular interprete correctamente el carácter. Por lo tanto, dentro de la cadena de la expresión regular, se requiere otra barra invertida, haciendo que en total necesites cuatro barras invertidas (\\\\).
        # \\\\ se convierte en una sola barra invertida \ cuando se evalúa la cadena en PHP.
        # La expresión regular /\\\\/ busca una barra invertida en el string.
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }
    if (file_exists("{$path}.php")) {
        require_once("{$path}.php");
    }
};
\spl_autoload_register($namespaces);
// $obj = new util\LocalPath();
$obj = new LocalPath();
$obj->wave();






$namespaces = function (string $path) {
    if (preg_match('/\\\\/', $path)) {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }
    if (\stream_resolve_include_path("{$path}.php") !== false) {
        require_once("{$path}.php");
    }
};
\spl_autoload_register($namespaces);
$obj = new util\LocalPath();
$obj->wave();
// Cuando usas file_exists() para verificar la existencia de un archivo antes de usar require_once, podrías no obtener resultados precisos si el archivo está ubicado en un directorio que no es el actual o no se especifica la ruta correctamente.
// Esto puede dar lugar a errores o a un comportamiento inesperado, ya que require_once podría encontrar y cargar el archivo, mientras que file_exists() podría devolver false porque no puede localizar la ruta.

// Explicación de file_exists()

// Cuando digo que file_exists() solo puede comprobar la existencia del archivo en rutas absolutas o relativas desde el directorio en el que se ejecuta el script, me refiero a lo siguiente:

// Ruta Absoluta: Es la ruta completa desde la raíz del sistema de archivos. Por ejemplo, /var/www/html/mi_archivo.php.
// Ruta Relativa: Es una ruta en relación con el directorio en el que se ejecuta el script. Por ejemplo, si tu script está en /var/www/html y usas file_exists('mi_archivo.php'), está buscando en el directorio actual.

// Si intentas verificar un archivo en un directorio que no está relacionado con la ubicación del script, file_exists() no lo encontrará.






// ¿Qué sucedería si quisiera admitir nombres de clase y espacios de nombres de estilo PEAR? Podría
// combinar mis implementaciones de carga automática en una única función personalizada. O bien, podría utilizar el
// hecho de que spl_autoload_register() apila sus funciones de carga automática:
$underscores = function (string $classname) {
    $path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
    $path = __DIR__ . "/$path";
    if (\stream_resolve_include_path("{$path}.php") !== false) {
        require_once("{$path}.php");
    }
};
