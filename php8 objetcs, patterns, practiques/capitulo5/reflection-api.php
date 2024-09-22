<?php
// Getting Started
// BRINDAN MAS FUNCIONABILIDADES, 
// La API Reflection de PHP es para PHP lo que el paquete java.lang.reflect es para Java. Consta de clases integradas para analizar propiedades, métodos y clases. Es similar
// en algunos aspectos a las funciones de objetos existentes, como get_class_vars(), pero es más
// flexible y proporciona muchos más detalles. También está diseñada para funcionar con las funciones orientadas a objetos de PHP, como el control de acceso, las interfaces y las clases abstractas, de una manera
// que las funciones de clase más antiguas y limitadas no pueden.
// The Reflection API can be used to examine more than just classes. For example,
// the ReflectionFunction class provides information about a given function, and
// ReflectionExtension yields insight about an extension compiled into the language.

// Reflection: Proporciona un método export() estático para resumir la información de la clase
// ReflectionAttribute Información contextual sobre clases, propiedades, constantes o parámetros
// ReflectionClass Información y herramientas de la clase
// ReflectionClassConstant Información sobre una constante
// ReflectionException Una clase de error
// ReflectionExtension Información de la extensión PHP
// ReflectionFunction Información y herramientas de la función
// ReflectionGenerator Información sobre un generador
// ReflectionMethod Información y herramientas del método de la clase
// ReflectionNamedType Información sobre el tipo de retorno de una función o método (los tipos de retorno de unión se describen con ReflectionUnionType)
// ReflectionObject Información y herramientas del objeto (hereda de ReflectionClass
// ReflectionParameter Información de argumentos del método
// ReflectionProperty Información de propiedad de clase
// ReflectionType Información sobre el tipo de retorno de una función o método
// ReflectionUnionType Una colección de objetos ReflectionType para un tipo de unión declaración
// ReflectionZendExtension Información de extensión PHP Zend

// Entre ellas, las clases de la API Reflection proporcionan un acceso en tiempo de ejecución sin precedentes
// a la información sobre los objetos, funciones y extensiones de sus scripts.
// La potencia y el alcance de la API Reflection significan que, por lo general, debería utilizarla en lugar de las funciones de clase y objeto. Pronto la encontrará indispensable como herramienta para probar
// clases. Es posible que desee generar diagramas de clases o documentación, por ejemplo, o
// que desee guardar información de objetos en una base de datos, examinando los métodos de acceso
// (obtención y establecimiento) de un objeto para extraer nombres de campos. La creación de un marco que invoque métodos
// en clases de módulos de acuerdo con un esquema de nombres es otro uso de Reflection.

// Ya ha encontrado algunas funciones para examinar los atributos de las clases. Son útiles, pero a menudo limitadas. 
// Aquí tiene una herramienta que está a la altura. ReflectionClass
// ofrece métodos que revelan información sobre cada aspecto de una clase determinada, ya sea
// una clase interna o definida por el usuario. El constructor de ReflectionClass acepta un nombre de clase o de interfaz (o una instancia de objeto) como único argumento:
require_once "../capitulo1-3/example/herenciaOptimize.php";

$prodclass = new \ReflectionClass(CdProduct::class);
print $prodclass;
// Una vez que haya creado un objeto ReflectionClass, puede volcar instantáneamente todo tipo de
// información sobre la clase, simplemente accediendo a ella en el contexto de cadena. 

// Un método de utilidad, Reflection::export(), alguna vez fue la forma estándar
// de volcar información de ReflectionClass. Esto quedó obsoleto en PHP 7.4 y
// se eliminó por completo en PHP 8.0

// Como puede ver, ReflectionClass proporciona un acceso notable a la información sobre
// una clase. La salida de cadena proporciona información resumida sobre casi todos los aspectos de
// CdProduct, incluido el estado de control de acceso de las propiedades y los métodos, los argumentos
// que requiere cada método y la ubicación de cada método dentro del documento de script.
// Compárelo con una función de depuración más establecida. La función var_dump() es
// una herramienta de propósito general para resumir datos. Debe crear una instancia de un objeto antes de
// poder extraer un resumen, e incluso entonces no proporciona nada parecido al detalle que proporciona ReflectionClass
$cd = new CdProduct("cd1", "bob", "bobbleson", 4, 50);
// var_dump($cd);
// var_dump() y su primo print_r() son herramientas increíblemente convenientes para exponer
// los datos en sus scripts. Sin embargo, para las clases y funciones, la API Reflection lleva las cosas a un nivel
// totalmente nuevo.