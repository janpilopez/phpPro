<?php
// <!-- Al usar la cláusula as, puedo cambiar el alias de Debug a coreDebug.
// Si estás escribiendo código en un espacio de nombres y quieres acceder a una clase, un rasgo o una interfaz que reside en el espacio raíz (sin espacio de nombres) (por ejemplo, las clases principales de PHP como
// Exception, Error, Closure), puedes simplemente anteponer una barra invertida al nombre. Aquí hay una
// clase declarada en el espacio raíz: -->

class TreeLister
{
    public static function helloWorld(): void
    {
        print "hello from root namespace\n";
    }
}

// La declaración namespace popp\ch05\batch04\util; en PHP se utiliza para definir el espacio de nombres (namespace) en el que se encuentra el código a continuación. Los espacios de nombres son una característica del lenguaje PHP que ayuda a organizar el código en grupos lógicos, evitando conflictos entre nombres de clases, funciones y constantes.
// namespace popp\ch05\batch04;

// use popp\ch05\batch04\util\TreeLister;
// use namespacesclass\TreeLister;


// El código con espacio de nombres declara su propia clase TreeLister. El código del cliente utiliza la
// versión local, especificando la ruta completa con una declaración de uso. Un nombre calificado con una
// sola barra invertida accede a una clase con un nombre similar en el espacio de nombres raíz.

// \TreeLister::helloWorld(); // access from root
// TreeLister::helloWorld(); // access local


// A continuación, se muestra el resultado del fragmento anterior:
// hello from popp\ch05\batch04\util
// hello from root namespace
// You can declare more than one namespace in the same file using the syntax you have
// already seen. You can also use an alternative syntax that uses braces with the namespace
// keyword:
// listing 05.20
namespace com\getinstance\util {
    class Debug
    {
        public static function helloWorld(): void
        {
            print "hello from Debug\n";
        }
    }
}

namespace other {
    \com\getinstance\util\Debug::helloWorld();
}
// If you must combine multiple namespaces in the same file, then this is the
// recommended practice. Usually, however, it’s considered best practice to define
// namespaces on a per-file basis.