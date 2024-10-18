<?php
// Factory Method Pattern
// Patrón de método de fábrica
// El diseño orientado a objetos enfatiza la clase abstracta por sobre la implementación. Es decir,
// trabaja con generalizaciones en lugar de especializaciones. El patrón de método de fábrica
// aborda el problema de cómo crear instancias de objetos cuando el código se centra en
// tipos abstractos. ¿La respuesta? Dejar que las clases especializadas se encarguen de la instanciación.
// El problema
// Imagina un proyecto de organizador personal que administra objetos Appointment, entre otros
// tipos de objetos. Tu grupo empresarial ha establecido una relación con otra empresa y
// debes comunicarle datos de citas mediante un formato llamado BloggsCal. Sin embargo, el
// grupo empresarial te advierte que es posible que tengas que enfrentarte a más formatos a medida que pase el tiempo.
// Si te quedas solo en el nivel de interfaz, puedes identificar a dos participantes de inmediato.
// Necesitas un codificador de datos que convierta tus objetos Appointment en un formato propietario.
// Llamemos a esa clase ApptEncoder. Necesitas una clase de administrador que recupere un
// codificador y tal vez trabaje con él para comunicarse con un tercero. Podrías llamarlo
// CommsManager. Usando la terminología del patrón, CommsManager es el creador y
// ApptEncoder es el producto. Puedes ver esta estructura en la Figura 9-3.
// abstract class ApptEncoder
// {
//     abstract public function encode(): string;
// }
// // listing 09.16
// class BloggsApptEncoder extends ApptEncoder
// {
//     public function encode(): string
//     {
//         return "Appointment data encoded in BloggsCal format\n";
//     }
// }
// // listing 09.17
// class CommsManagerV1
// {
//     public function getApptEncoder(): ApptEncoder
//     {
//         return new BloggsApptEncoder();
//     }
// }

// class CommsManagerv2
// {
//     public const BLOGGS = 1;
//     public const MEGA = 2;
//     public function __construct(private int $mode) {}
//     public function getApptEncoder(): ApptEncoder
//     {
//         switch ($this->mode) {
//             case (self::MEGA):
//                 return new MegaApptEncoder();
//             default:
//                 return new BloggsApptEncoder();
//         }
//     }
// }
// // listing 09.19
// class MegaApptEncoder extends ApptEncoder
// {
//     public function encode(): string
//     {
//         return "Appointment data encoded in MegaCal format\n";
//     }
// }
// $man = new CommsManagerv2(CommsManager::MEGA); // EL :: HACE REFERENCIA A LA CONSTANTE DE DECLARADA POR LO QUE SU VLOR ES 2
// print (get_class($man->getApptEncoder())) . "\n";
// $man = new CommsManagerv2(CommsManager::BLOGGS);
// print (get_class($man->getApptEncoder())) . "\n";

// // Utilizo indicadores constantes para definir dos modos en los que se puede ejecutar el script: MEGA y
// // BLOGGS. Utilizo una declaración switch en el método getApptEncoder() para probar la propiedad $mode
// // y crear una instancia de la implementación adecuada de ApptEncoder.
// // Este enfoque tiene pocos errores. A veces, los condicionales se consideran ejemplos de “olores de código” erróneos, pero la creación de objetos a menudo requiere un condicional en algún
// // momento. Debería ser menos optimista si ve que se introducen condicionales duplicados en su
// // código. La clase CommsManager proporciona funcionalidad para comunicar datos de calendario.
// class CommsManagerv3
// {
//     public const BLOGGS = 1;
//     public const MEGA = 2;
//     public function __construct(private int $mode) {}
//     public function getApptEncoder(): ApptEncoder
//     {
//         switch ($this->mode) {
//             case (self::MEGA):
//                 return new MegaApptEncoder();
//             default:
//                 return new BloggsApptEncoder();
//         }
//     }
//     public function getHeaderText(): string
//     {
//         switch ($this->mode) {
//             case (self::MEGA):
//                 return "MegaCal header\n";
//             default:
//                 return "BloggsCal header\n";
//         }
//     }
// }
// Como puede ver, la necesidad de admitir la salida del encabezado me ha obligado a duplicar la
// prueba condicional del protocolo. Esto se volverá difícil de manejar a medida que agregue nuevos protocolos, especialmente si
// también agrego un método getFooterText().


// ###########Implementación
// El patrón Factory Method separa las clases creadoras de los productos que están diseñadas
// para generar. La clase creadora es una clase fábrica que define un método para generar un objeto
// producto. Si no se proporciona una implementación predeterminada, se deja que las clases hijas creadoras realicen la instanciación. Normalmente, cada subclase creadora instancia una clase hija
// producto paralela. Puedo rediseñar CommsManager como una clase abstracta. De esa manera, mantengo una superclase flexible
// y pongo todo mi código específico del protocolo en las subclases concretas. Puede ver
// esta alteración en la Figura 9-4.
abstract class ApptEncoder
{
    abstract public function encode(): string;
}
// listing 09.23
class BloggsApptEncoder extends ApptEncoder
{
    public function encode(): string
    {
        return "Appointment data encoded in BloggsCal format\n";
    }
}
// listing 09.24
abstract class CommsManager
{
    abstract public function getHeaderText(): string;
    abstract public function getApptEncoder(): ApptEncoder;
    abstract public function getFooterText(): string;
}
// listing 09.25
class BloggsCommsManager extends CommsManager
{
    public function getHeaderText(): string
    {
        return "BloggsCal header\n";
    }
    public function getApptEncoder(): ApptEncoder
    {
        return new BloggsApptEncoder();
    }
    public function getFooterText(): string
    {
        return "BloggsCal footer\n";
    }
}
// listing 09.26
$mgr = new BloggsCommsManager();
print $mgr->getHeaderText();
print $mgr->getApptEncoder()->encode();
print $mgr->getFooterText();
// Por lo tanto, cuando necesito implementar MegaCal, brindarle soporte es simplemente una cuestión de
// escribir una nueva implementación para mis clases abstractas. La Figura 9-5 muestra las
// clases de MegaCal.


// Consecuencias
// Observe que las clases de creación reflejan la jerarquía de productos. Esta es una consecuencia común del patrón Factory Method y a algunos no les gusta como un tipo especial de duplicación de código. Otro problema es la posibilidad de que el patrón pueda fomentar la subclasificación innecesaria. Si su única razón para subclasificar un creador es implementar el patrón Factory Method, es posible que deba pensarlo nuevamente (por eso introduje las restricciones de encabezado y pie de página en el ejemplo aquí).
// Me he centrado solo en las citas en mi ejemplo. Si lo extiendo un poco para
// incluir elementos de tareas pendientes y contactos, me enfrento a un nuevo problema. Necesito una estructura que
// maneje conjuntos de implementaciones relacionadas a la vez.
// El patrón Factory Method se usa a menudo con el patrón Abstract Factory, como verá en la siguiente sección.