<?php

// namespace popp\ch05\batch04;
// Usa require_once para incluir el archivo que define la clase.
// Usa use para referenciar la clase con su espacio de nombres.
// Recomendación
// Si tu proyecto crece, considera utilizar Composer para manejar el autoloading, ya que facilita mucho el proceso de carga de clases sin necesidad de incluir archivos manualmente.
require_once __DIR__ . "/namespacesClass.php";
require_once __DIR__ . "/namespaces.php";
// use popp\ch05\batch04\util\TreeLister;//si solo llamamos con use habra error ya que no trae las funcionaes de la clase, habria que hacer autoload o require_once mejor

class llamarClase
{
    public static function helloWorld(): void
    {
        print "hello from 1" . __NAMESPACE__ . "\n";
        // TreeLister::helloWorld();
    }
}
echo llamarClase::helloWorld();
//si comentamos linea 8 y 9 podemos ver que cada una direcciona a un metodo diferente de la clase treeslist de diferentes archivos/namespaces
// TreeLister::helloWorld(); // access from root
\TreeLister::helloWorld(); // access from root