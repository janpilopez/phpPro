<?php
// El patrón Singleton
// El patrón Singleton asegura que una clase tenga solo una instancia y proporciona un punto de acceso global a esa instancia

// La variable global es uno de los grandes problemas de los programadores orientados a objetos.
// Las razones ya deberían resultarle familiares. Las variables globales vinculan las clases a su contexto, socavando la encapsulación (consulte los Capítulos 6 y 8 para obtener más información al respecto). Una clase que depende de variables globales se vuelve imposible de extraer de una aplicación y usar en otra,
// sin asegurarse primero de que la nueva aplicación define las mismas variables globales.
// Aunque esto no es deseable, la naturaleza desprotegida de las variables globales puede ser
// un problema mayor. Una vez que comience a depender de las variables globales, tal vez sea solo
// cuestión de tiempo antes de que una de sus bibliotecas declare una variable global que entre en conflicto con otra declarada en otro lugar. Ya ha visto que, si no está utilizando espacios de nombres,
// PHP es vulnerable a conflictos de nombres de clases. Pero esto es mucho peor. PHP no le advertirá
// cuando las variables globales colisionen. Lo primero que sabrá al respecto será cuando su script comience
// a comportarse de manera extraña. Peor aún, es posible que no notes ningún problema en tu entorno de desarrollo. Sin embargo, al usar variables globales, potencialmente dejas a tus usuarios expuestos a nuevos e interesantes conflictos cuando intentan implementar tu biblioteca junto con otras.
// Sin embargo, las variables globales siguen siendo una tentación. Esto se debe a que hay momentos en que el pecado
// inherente al acceso global parece un precio que vale la pena pagar para dar a todas tus clases
// acceso a un objeto.
// Como insinué, los espacios de nombres brindan cierta protección contra esto. Al menos puedes limitar el alcance de las variables a un paquete, lo que significa que es menos probable que las bibliotecas de terceros entren en conflicto con tu propio sistema. Aun así, el riesgo de colisión existe dentro del propio espacio de nombres.

// Además de las variables, las constantes y las funciones también están limitadas a
// espacios de nombres. Cuando se invoca una variable, constante o función sin un espacio de nombres explícito, PHP primero la busca localmente y luego en el espacio de nombres global.

// El problema
// Los sistemas bien diseñados generalmente pasan instancias de objetos a través de llamadas de método. Cada
// clase conserva su independencia del contexto más amplio, colaborando con otras partes
// del sistema a través de líneas claras de comunicación. A veces, sin embargo, se descubre que
// esto obliga a utilizar algunas clases como conductos para objetos que no les conciernen,
// introduciendo dependencias en nombre del buen diseño.

// Imagínese una clase Preferences que contenga información a nivel de aplicación. Podríamos utilizar
// un objeto Preferences para almacenar datos como cadenas DSN (los nombres de origen de datos son cadenas
// que contienen la información necesaria para conectarse a una base de datos), raíces URL, rutas de archivos, etc. Este es el tipo de información que variará de una instalación a otra. El
// objeto también puede utilizarse como un tablón de anuncios, una ubicación central para mensajes que podrían ser
// establecidos o recuperados por objetos no relacionados de otro modo en un sistema.
// Pasar un objeto Preferences de un objeto a otro puede no ser siempre una
// buena idea. Muchas clases que de otra manera no usan el objeto podrían verse obligadas a aceptarlo
// simplemente para poder pasarlo a los objetos con los que trabajan. Este es otro
// tipo de acoplamiento.
// También debe asegurarse de que todos los objetos en su sistema estén trabajando con el mismo objeto
// Preferences. No desea que los objetos establezcan valores en un objeto, mientras que otros
// leen de uno completamente diferente.
// Resumamos las fuerzas en este problema:
// • Un objeto Preferences debe estar disponible para cualquier objeto en su
// sistema.
// • Un objeto Preferences no debe almacenarse en una variable global, que
// puede sobrescribirse.
// • No debe haber más de un objeto Preferences en juego
// en el sistema. Esto significa que el objeto Y puede establecer una propiedad en el objeto
// Preferences y el objeto Z puede recuperar la misma propiedad,
// sin que ninguno de los dos se comunique directamente con el otro (suponiendo que ambos tienen
// acceso al objeto Preferences).
// Implementación
// Para solucionar este problema, puedo empezar por afirmar el control sobre la instanciación de objetos. Aquí, creo una clase que no se puede instanciar desde fuera de sí misma. Puede parecer difícil, pero es simplemente una cuestión de definir un constructor privado:
// listing 09.12
class PreferencesV1
{
    private array $props = [];
    private function __construct() {}
    public function setProperty(string $key, string $val): void
    {
        $this->props[$key] = $val;
    }
    public function getProperty(string $key): string
    {
        return $this->props[$key];
    }
}
// Por supuesto, en este punto, la clase Preferences es completamente inutilizable. He llevado la
// restricción de acceso a un nivel absurdo. Debido a que el constructor se declara privado, ningún código de cliente
// puede instanciar un objeto a partir de él. Por lo tanto, los métodos setProperty() y getProperty()
// son redundantes. Aquí, utilizo un método estático y una propiedad estática para mediar en la instanciación de objetos:
class Preferences
{
    private array $props = [];
    private static Preferences $instance;
    private function __construct() {}
    public static function getInstance(): Preferences
    {
        if (empty(self::$instance)) {
            self::$instance = new Preferences();
        }
        return self::$instance;
            // 2. Referencias en PHP
            // En PHP, las variables son referencias a valores. Cuando asignas un objeto a una variable, la variable almacena una referencia a ese objeto, no el objeto en sí. Por lo tanto, cuando haces unset($pref), lo que haces es eliminar la referencia $pref a la instancia del objeto, pero no destruyes el objeto en sí.
            // 
            // 3. Comportamiento de unset()
            // Cuando ejecutas unset($pref):
            // Elimina la referencia local: Solo elimina la variable $pref de tu contexto actual. Después de unset(), no puedes acceder a la instancia de Preferences a través de $pref.
            // La instancia sigue viva: La instancia de Preferences sigue existiendo en la memoria porque fue creada y almacenada en la propiedad estática $instance de la clase. La lógica de getInstance() garantiza que siempre que se llame, se devuelva la misma instancia.
    }
    public function setProperty(string $key, string $val): void
    {
        $this->props[$key] = $val;
    }
    public function getProperty(string $key): string
    {
        return $this->props[$key];
    }
}

// La propiedad $instance es privada y estática, por lo que no se puede acceder a ella desde fuera
// de la clase. Sin embargo, el método getInstance() sí tiene acceso. Como getInstance() es público
//  y estático, se puede llamar a través de la clase desde cualquier parte de un script:
$pref = Preferences::getInstance();
$pref->setProperty("name", "matt");
unset($pref); // remove the reference
// Estás llamando de nuevo al método getInstance(), que verifica si self::$instance ya ha sido creada. Como la instancia sigue existiendo, getInstance() devuelve la misma instancia, y puedes acceder a las propiedades establecidas anteriormente, como "name".
$pref2 = Preferences::getInstance();
print $pref2->getProperty("name") . "\n"; // demonstrate value is not lost

// $pref1 = PreferencesV1:: NO SE PUEDE OBTENER NADA

// El resultado es el valor único que agregamos inicialmente al objeto Preferencias, disponible
// a través de un acceso independiente: matt

// Propósito de unset($pref)
// Eliminar la Referencia: Al usar unset($pref), estás eliminando la referencia local a la instancia de Preferences. 
// Sin embargo, esto no destruye la instancia en sí, ya que el patrón Singleton asegura que siempre haya una única 
// instancia activa.
// Verificación de la Persistencia de Datos: La línea print $pref2->getProperty("name") demuestra que, aunque se 
// eliminó la referencia $pref, los datos (en este caso, la propiedad "name") no se han perdido. Esto es porque la 
// propiedad se almacena en la instancia única de Preferences, que sigue existiendo incluso después de que se elimine 
// la referencia a ella.
// ¿Por Qué Hacer Esto?
// Gestión de Memoria: En algunos contextos, puede ser útil liberar referencias a objetos si ya no las necesitas, 
// aunque en este caso particular, el uso de unset() no es estrictamente necesario para la gestión de memoria, dado 
// que el objeto persiste como parte del patrón Singleton.
// Claridad del Código: Usar unset() puede ayudar a hacer más claro que ya no se necesita la referencia, lo que puede 
// ser útil para mantener el código limpio y comprensible, especialmente en contextos más complejos.


// Un método estático no puede acceder a las propiedades de un objeto porque, por definición, se invoca
// en una clase y no en un contexto de objeto. Sin embargo, puede acceder a una propiedad estática. Cuando se llama
// a getInstance(), verifico la propiedad Preferences::$instance. Si está vacía,
// entonces creo una instancia de la clase Preferences y la guardo en la propiedad. Luego,
// devuelvo la instancia al código de llamada. Debido a que el método estático getInstance() es parte
// de la clase Preferences, no tengo problemas para crear una instancia de un objeto Preferences, incluso
// aunque el constructor sea privado.
// La Figura 9-2 muestra el patrón Singleton.

// Consecuencias
// Entonces, ¿cómo se compara el enfoque Singleton con el uso de una variable global? Primero, las malas
// noticias. Tanto los Singleton como las variables globales son propensos a un mal uso. Debido a que se puede acceder a los Singleton
// desde cualquier parte de un sistema, pueden servir para crear dependencias
// que pueden ser difíciles de depurar. Si se cambia un Singleton, las clases que lo usan pueden verse afectadas.
// Las dependencias no son un problema en sí mismas. Después de todo, creamos una dependencia
// cada vez que declaramos que un método requiere un argumento de un tipo particular. El
// problema es que la naturaleza global del Singleton permite que un programador pase por alto las líneas de
// comunicación definidas por las interfaces de clase. Cuando se utiliza un Singleton, la dependencia se oculta
// dentro de un método y no se declara en su firma. Esto puede dificultar el seguimiento de las relaciones dentro de un sistema. Por lo tanto, las clases Singleton deben implementarse
// con moderación y cuidado.
// Sin embargo, creo que el uso moderado del patrón Singleton puede mejorar
// el diseño de un sistema, ahorrándole horribles contorsiones al pasar objetos
// innecesariamente por su sistema.

// Los singletons representan una mejora con respecto a las variables globales en un contexto orientado a objetos. No se puede sobrescribir un singleton con el tipo de datos incorrecto. Además, se pueden agrupar operaciones y conjuntos de datos dentro de una clase Singleton, lo que la convierte en una opción
// muy superior a una matriz asociativa o un conjunto de variables escalares.