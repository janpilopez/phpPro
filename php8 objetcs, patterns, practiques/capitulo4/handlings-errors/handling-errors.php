<?php
//PEAR_ERROR YA EXISTIA PERO NO ES MUY COMPLETO LUEGO SE INTRODUJO LA CLASE EXCEPTION QUE MANEJA LOS ERRORES DE UNA FORMA MAS COMPLETA
// PHP 5 introduced exceptions to PHP, a radically different way of handling error conditions.
// Different for PHP, that is. You will find them hauntingly familiar if you have Java or C++
// experience. Exceptions address all of the issues that I have raised so far in this section.
// An exception is a special object instantiated from the built-in Exception class (or
// from a derived class).
// Objects of type Exception are designed to hold and report error information.
// The Exception class constructor accepts two optional arguments, a message
// string and an error code. The class provides some useful methods for analyzing error
// conditions. These are described in Table 4-1.
// The Exception class is fantastically useful for providing error notification and debugging
// information (the getTrace() and getTraceAsString() methods are particularly helpful in
// this regard). In fact, it is almost identical to the PEAR_Error class that was discussed earlier.
// There is much more to an exception than the information it holds, though.

// Métodos públicos de la clase Exception
// Método              Descripción
// getMessage()        Obtiene la cadena de mensaje que se pasó al constructor
// getCode()           Obtiene el entero de código que se pasó al constructor
// getFile()           Obtiene el archivo en el que se generó la excepción
// getLine()           Obtiene el número de línea en el que se generó la excepción
// getPrevious()       Obtiene un objeto Exception anidado
// getTrace()          Obtiene una matriz multidimensional que rastrea las llamadas de método que llevaron a la
// excepción, incluidos los datos de método, clase, archivo y argumento
// getTraceAsString()  Obtiene una versión de cadena de los datos devueltos por getTrace()
// __toString()        Se llama automáticamente cuando el objeto Exception se usa en un contexto de cadena.
// Devuelve una cadena que describe los detalles de la excepción

#########Throwing an Exception
// La palabra clave throw se utiliza junto con un objeto Exception. Detiene la ejecución
// del método actual y pasa la responsabilidad de manejar el error al código que lo llama.
class Handling_errors
{
    public function __construct(private string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception("file '{$file}' does not exist");
        }
        $this->xml = simplexml_load_file($file);
    }
    // The write() method can use a similar construct:
    // listing 04.64
    public function write(): void
    {
        if (!is_writeable($this->file)) {
            throw new \Exception("file '{$this->file}' is not writeable");
        }
        print "{$this->file} is apparently writeable\n";
        file_put_contents($this->file, $this->xml->asXML());
        // listing 04.65

        try {
            $conf = new Conf('/tmp/conf01.xml');
            //$conf = new Conf( "/root/unwriteable.xml" );
            //$conf = new Conf( "nonexistent/not_there.xml" );
            print 'user: ' . $conf->get('user') . "\n";
            print 'host: ' . $conf->get('host') . "\n";
            $conf->set('pass', 'newpass');
            $conf->write();
        } catch (\Exception $e) {
            // handle error in some way
        }
    }
}


// } catch (\Exception $e) {
    // handle error in some way
    // or
    // throw new \Exception("Conf error: " . $e->getMessage());
    // }
// Alternatively, you can just rethrow the exception you have been given:
// try {
    // $conf = new Conf("nonexistent/not_there.xml");
    // } catch (\Exception $e) {
    // handle error...
    // or rethrow
    // throw $e;
// }


#NEW PHP 8
// If you have no need of the Exception object itself in your error handling, you can, as
// of PHP 8, omit the exception argument altogether and just specify the type:
class ExampleError8
{
    public function php8(){
        try {
            $conf = new Conf("nonexistent/not_there.xml");
        } catch (\Exception) {
            // handle error without using the Exception object
        }
    }
}