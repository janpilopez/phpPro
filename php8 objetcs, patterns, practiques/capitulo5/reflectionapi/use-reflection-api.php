<?php
class Person
{
    public $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
// listing 05.77
interface Module
{
    public function execute(): void;
}
// listing 05.78
class FtpModule implements Module
{
    public function setHost(string $host): void
    {
        print "FtpModule::setHost(): $host\n";
    }
    public function setUser(string|int $user): void
    {
        print "FtpModule::setUser(): $user\n";
    }
    public function execute(): void
    {
        // do things
    }
}
class PersonModule implements Module
{
    public function setPerson(Person $person): void
    {
        print "PersonModule::setPerson(): {$person->name}\n";
    }
    public function execute(): void
    {
        // do things
    }
}

class ModuleRunner
{
    private array $configData = [
        PersonModule::class => ['person' => 'bob'],
        FtpModule::class => [
            'host' => 'example.com',
            'user' => 'anon'
        ]
    ];
    private array $modules = [];
    // ...
    public function init(): void
    {
        $interface = new \ReflectionClass(Module::class);
        foreach ($this->configData as $modulename => $params) {
            $module_class = new \ReflectionClass($modulename);

            if (! $module_class->isSubclassOf($interface)) {
                throw new Exception("unknown module type: $modulename");
            }
            $module = $module_class->newInstance();
            foreach ($module_class->getMethods() as $method) {
                $this->handleMethod($module, $method, $params);
                // we cover handleMethod() in a future listing!
            }
            array_push($this->modules, $module);
        }
    }
    public function handleMethod(
        Module $module,
        \ReflectionMethod $method,
        array $params
    ): bool {
        $name = $method->getName();
        $args = $method->getParameters();
        if (count($args) != 1 || substr($name, 0, 3) != "set") {
            return false;//verifica solo si es un parametro, si no retorna false
            // En resumen, este bloque de código está diseñado para validar que un método específico sea un "setter" adecuado, siguiendo el siguiente criterio:
            // Debe tener exactamente un parámetro: Esto es típico de los métodos "setter", que generalmente se utilizan para establecer el valor de una propiedad. Su nombre debe comenzar con "set": Esto es una convención común para los métodos que establecen valores en una clase.        }
        }
        $property = strtolower(substr($name, 3));
        if (! isset($params[$property])) {
            //verifica si existe la propiedad en el array de parametros, si no retorna false
            return false;
        }
        if (! $args[0]->hasType()) {
            // Si el método no tiene tipo definido, simplemente invoca el método pasando el valor de $params.
            $method->invoke($module, $params[$property]);
            return true;
        }
        $arg_type = $args[0]->getType();
        // Si tiene un tipo definido y es una clase, crea una instancia de esa clase usando el valor correspondiente de $params y luego invoca el método.
        if (! ($arg_type instanceof \ReflectionUnionType) && class_exists(
            $arg_type->getName()
            // !($arg_type instanceof \ReflectionUnionType): Verifica que el tipo no sea un tipo de unión. Un tipo de unión en PHP puede ser múltiples tipos, como string|int. Si no es un tipo de unión, se procede a la siguiente verificación.
            // class_exists($arg_type->getName()): Comprueba si existe una clase con el nombre del tipo que se obtuvo. Esto es importante porque se espera que se pueda crear una instancia de esa clase más adelante.
        )) {
            $method->invoke(
                $module,
                (new \ReflectionClass($arg_type->getName()))->newInstance(
                    $params[$property]
                )
                // Aquí se crea una nueva instancia de la clase correspondiente al tipo del argumento.
                // new \ReflectionClass($arg_type->getName()) crea un objeto ReflectionClass para la clase.
                // ->newInstance($params[$property]) crea una nueva instancia de esa clase, pasando el valor correspondiente de $params[$property] al constructor de la clase. Esto asume que el constructor de la clase requiere un argumento, que es el valor que se extrae de $params.
            );
        } else {
            $method->invoke($module, $params[$property]);
            // Si la condición inicial no se cumple (es decir, si el tipo es un tipo de unión o no es una clase válida), el método se invoca directamente con el valor $params[$property], sin crear una instancia de clase.
            // Esto puede ser útil si el tipo es un tipo primitivo (como string, int, etc.) o si el método acepta otros tipos que no requieren instanciación
        }
        return true;
    }
}


$test = new ModuleRunner();
$test->init();

// Este código en PHP define una serie de clases e interfaces relacionadas con módulos que se pueden ejecutar, utilizando un patrón de diseño orientado a objetos. Vamos a desglosar cada parte:
// Clases y Estructuras

//     Clase Person:
//         Representa a una persona con una propiedad name.
//         Su constructor acepta un string que inicializa el nombre de la persona.

//     Interfaz Module:
//         Define el método execute(), que todas las clases que implementen esta interfaz deben definir.

//     Clase FtpModule:
//         Implementa la interfaz Module.
//         Contiene métodos setHost y setUser para configurar el host y el usuario, respectivamente.
//         Implementa el método execute() (aunque está vacío en este momento).

//     Clase PersonModule:
//         También implementa la interfaz Module.
//         Tiene un método setPerson que acepta un objeto de la clase Person.
//         También implementa el método execute().

//     Clase ModuleRunner:
//         Gestiona la creación y ejecución de módulos.
//         Tiene una propiedad $configData, que es un array que contiene la configuración de los módulos a inicializar.
//         Tiene una propiedad $modules que almacena las instancias de los módulos creados.

// Método init()

//     El método init() se encarga de:
//         Crear una instancia de ReflectionClass para la interfaz Module.
//         Iterar sobre el array $configData para crear instancias de los módulos.
//         Verificar si cada clase de módulo implementa la interfaz Module usando isSubclassOf.
//         Crear una instancia de cada módulo y luego iterar sobre sus métodos.

// Método handleMethod()

// Este método es clave en el proceso de configuración de los módulos:

//     Parámetros:
//         $module: la instancia del módulo.
//         $method: el método de la clase del módulo que se va a manejar.
//         $params: los parámetros de configuración para ese módulo.

// Lógica del Método:

//     Validación del Método:
//         Verifica si el método tiene un solo parámetro y si su nombre comienza con "set".
//         Si no, devuelve false.

//     Extracción del Nombre del Propósito:
//         El nombre del parámetro se deriva del nombre del método eliminando el prefijo "set" y convirtiéndolo a minúsculas.

//     Verificación de Parámetros:
//         Si no hay una entrada correspondiente en $params, devuelve false.

//     Invocación del Método:
//         Si el método no tiene tipo definido, simplemente invoca el método pasando el valor de $params.
//         Si tiene un tipo definido y es una clase, crea una instancia de esa clase usando el valor correspondiente de $params y luego invoca el método.
//         Si el tipo es un ReflectionUnionType (un tipo que puede ser más de uno), simplemente invoca el método con el valor de $params.

// Ejecución

// Finalmente, el código crea una instancia de ModuleRunner y llama a su método init(), que inicia el proceso de configuración y ejecución de los módulos según la configuración definida en $configData.

// Finalmente, se crea una instancia de ModuleRunner y se llama al método init(), lo que inicia el proceso de configuración y posible ejecución de los módulos definidos en $configData.
// Resumen

// El código establece un sistema para definir, inicializar y ejecutar módulos de manera flexible utilizando programación orientada a objetos y reflexión en PHP. Los módulos pueden tener diferentes configuraciones y comportamientos, y el ModuleRunner se encarga de orquestar su creación y ejecución.