<?php
// Desacoplamiento
// En el Capítulo 6, vio que tiene sentido crear componentes independientes. Un sistema
// con clases altamente interdependientes puede ser difícil de mantener. Un cambio en una ubicación
// puede requerir una cascada de cambios relacionados en todo el sistema.
// El problema
// La reutilización es uno de los objetivos clave del diseño orientado a objetos, y el acoplamiento estricto
// es su enemigo. Puede diagnosticar un acoplamiento estricto cuando vea que un cambio en un
// componente de un sistema requiere muchos cambios en otras partes. Debe aspirar a crear componentes independientes, de modo que pueda realizar cambios sin un efecto dominó
// de consecuencias no deseadas. Cuando modifica un componente, el grado en que es
// independiente está relacionado con la probabilidad de que sus cambios provoquen que otras partes de su
// sistema fallen.
// Al aplicar el patrón Strategy, destilé mis algoritmos de costos en el tipo CostStrategy, ubicándolos detrás de una interfaz común e implementando cada uno solo una vez.
// Puede ocurrir otro tipo de acoplamiento cuando muchas clases en un sistema están integradas
// explícitamente en una plataforma o entorno. Digamos que está construyendo un sistema
// que funciona con una base de datos MySQL, por ejemplo. Puede usar métodos como
// mysqli::query() para comunicarse con el servidor de base de datos.
// Si se le solicita que implemente el sistema en un servidor que no admite
// MySQL, puede convertir todo su proyecto para usar SQLite. Sin embargo, se vería obligado a
// realizar cambios en todo su código y enfrentar la perspectiva de mantener dos versiones
// paralelas de su aplicación.
// El problema aquí no es la dependencia del sistema de una plataforma externa. Tal
// dependencia es inevitable. Debe trabajar con código que se comunica con una base de datos. El
// problema surge cuando dicho código está disperso en todo un proyecto. Hablar con bases de datos
// no es la responsabilidad principal de la mayoría de las clases de un sistema, por lo que la mejor estrategia es
// extraer dicho código y agruparlo detrás de una interfaz común. De esta manera, se
// promueve la independencia de las clases. Al mismo tiempo, al concentrar el código de
// la puerta de enlace en un solo lugar, se hace mucho más fácil cambiar a una nueva plataforma sin
// perturbar el sistema en general. Este proceso, el ocultamiento de la implementación detrás de una interfaz limpia, se conoce como encapsulación. La biblioteca de bases de datos Doctrine resuelve este problema
// con el proyecto DBAL (capa de abstracción de base de datos). Esto proporciona un único punto de acceso
// para múltiples bases de datos.
// La clase DriverManager proporciona un método estático llamado getConnection() que
// acepta una matriz de parámetros. Según la composición de esta matriz, devuelve una implementación particular de una interfaz llamada Doctrine\DBAL\Driver. Puede ver la estructura de la clase en la Figura 8-5.


// Figura 8-5. El paquete DBAL desacopla el código del cliente de los objetos de la base de datos
// Nota Los atributos y operaciones estáticos deben estar subrayados en el UML.
// El paquete DBAL, entonces, le permite desacoplar el código de su aplicación de los detalles
// de su plataforma de base de datos. Debería poder ejecutar un solo sistema con MySQL,
// SQLite, MSSQL y otros sin cambiar una línea de código (aparte de los parámetros de configuración, por supuesto).

// Aflojar el acoplamiento
// Para manejar el código de la base de datos de manera flexible, debe desacoplar la lógica de la aplicación de las
// características específicas de la plataforma de base de datos que utiliza. Verá muchas oportunidades para este tipo de
// separación de componentes en sus propios proyectos.
// Imagínese, por ejemplo, que el sistema de lecciones debe incorporar un componente de registro
// para agregar nuevas lecciones al sistema. Como parte del procedimiento de registro, se debe notificar a un administrador cuando se agrega una lección. Los usuarios del sistema no pueden
// ponerse de acuerdo sobre si esta notificación debe enviarse por correo electrónico o por mensaje de texto. De hecho,
// son tan discutidores que sospecha que podrían querer cambiar a un nuevo modo de
// comunicación en el futuro. Es más, quieren recibir notificaciones de todo tipo de cosas,
// de modo que un cambio en el modo de notificación en un lugar significará una alteración similar en muchos otros lugares.
// Si ha codificado de forma rígida llamadas a una clase Mailer o Texter, entonces su sistema está
// estrechamente acoplado a un modo de notificación particular, tal como estaría estrechamente acoplado a una
// plataforma de base de datos mediante el uso de una API de base de datos especializada.

//include Example.php que es la clase universal que trabajaremos este capitulo
include 'Example.php';

class RegistrationMgr
{
    public function register(Lesson $lesson): void
    {
        // do something with this Lesson
        // now tell someone
        $notifier = Notifier::getNotifier();
        $notifier->inform("new lesson: cost ({$lesson->cost()})");
    }
}
// listing 08.14
abstract class Notifier
{
    public static function getNotifier(): Notifier
    {
        // acquire concrete class according to
        // configuration or other logic
        if (rand(1, 2) === 1) {
            return new MailNotifier();
        } else {
            return new TextNotifier();
        }
    }
    abstract public function inform($message): void;
}

class MailNotifier extends Notifier
{
    public function inform($message): void
    {
        print "MAIL notification: {$message}\n";
    }
}
// listing 08.16
class TextNotifier extends Notifier
{
    public function inform($message): void
    {
        print "TEXT notification: {$message}\n";
    }
}

$lessons1 = new Seminar(4, new TimedCostStrategy());
$lessons2 = new Lecture(4, new FixedCostStrategy());
$mgr = new RegistrationMgr();
$mgr->register($lessons1);
$mgr->register($lessons2);

// La clase Notifier separa el código del cliente de las implementaciones de NotifierCódigo para una interfaz, no para una implementación
// Este principio es uno de los temas omnipresentes de este libro. En el Capítulo 6
// (y en la última sección) viste que puedes ocultar diferentes implementaciones detrás de la interfaz común
// definida en una superclase. El código del cliente puede entonces requerir un objeto del tipo de la superclase en lugar del de una clase implementadora, sin preocuparse por la implementación específica
// que realmente está obteniendo.
// Las declaraciones condicionales paralelas, como las que extraje de Lesson::cost() y
// Lesson::chargeType(), son una señal común de que se necesita polimorfismo. Hacen que el código sea difícil de mantener porque un cambio en una expresión condicional requiere
// un cambio en sus hermanas. Ocasionalmente se dice que las declaraciones condicionales implementan una “herencia simulada”.
// Al colocar los algoritmos de costos en clases separadas que implementan CostStrategy,
// elimino la duplicación. También lo hago mucho más fácil si necesito agregar nuevas estrategias de costos en el futuro.

// public function __construct(private int $duration, private
// FixedCostStrategy $costStrategy)
// {
// }

// Hay dos problemas que surgen de la decisión de diseño en este ejemplo. En primer lugar,
// el objeto Lesson ahora está vinculado a una estrategia de costos específica, lo que cierra mi
// capacidad de componer componentes dinámicos. En segundo lugar, la referencia explícita a la clase
// FixedPriceStrategy me obliga a mantener esa implementación particular.
// Al requerir una interfaz común, puedo combinar un objeto Lesson con cualquier
// implementación de CostStrategy:

// public function __construct(private int $duration, private CostStrategy
// $costStrategy)
// {
// }