<?php
// Generación de objetos
// La creación de objetos es un asunto complicado. Por ello, muchos diseños orientados a objetos se ocupan de clases abstractas
// agradables y claras, aprovechando la impresionante flexibilidad que ofrece el
// polimorfismo (el cambio de implementaciones concretas en tiempo de ejecución). Sin embargo, para lograr esta
// flexibilidad, debo idear estrategias para la generación de objetos. Este es el tema que analizaré en este capítulo.

// Este capítulo cubrirá los siguientes patrones:
// • El patrón Singleton: una clase especial que genera una (y sólo una) instancia de objeto
// • El patrón Factory Method: creación de una jerarquía de herencia de clases creadoras
// • El patrón Abstract Factory: agrupación de la creación de productos funcionalmente relacionados
// • El patrón Prototype: uso de clones para generar objetos
// • El patrón Service Locator: solicitud de objetos a su sistema
// • El patrón Dependency Injection: permitir que su sistema le proporcione objetos

// Problemas y soluciones en la generación de objetos
// La creación de objetos puede ser un punto débil en el diseño orientado a objetos. En el capítulo anterior,
// viste el principio “Codifica para una interfaz, no para una implementación”. Con este fin,
// se te anima a trabajar con supertipos abstractos en tus clases. Esto hace que el código sea más
// flexible, permitiéndote usar objetos instanciados de diferentes subclases concretas en
// tiempo de ejecución. Esto tiene el efecto secundario de que la instanciación de objetos se pospone.

abstract class Employee
{
    public function __construct(protected string $name) {}
    abstract public function fire(): void;
}
// listing 09.02
class Minion extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll clear my desk\n";
    }
}
// Now, here’s a client class that works with Minion objects:
class NastyBoss
{
    private array $employees = [];
    public function addEmployee(string $employeeName): void
    {
        $this->employees[] = new Minion($employeeName);
    }
    public function projectFails(): void
    {
        if (count($this->employees) > 0) {
            $emp = array_pop($this->employees);
            $emp->fire();
        }
    }
}
// Time to put the code through its paces:
$boss = new NastyBoss();
$boss->addEmployee("harry");
$boss->addEmployee("bob");
$boss->addEmployee("mary");
$boss->projectFails();

// Como puede ver, defino una clase base abstracta, Employee, con una implementación
// reducida, Minion. Dada una cadena de nombre, el método NastyBoss::addEmployee()
// instancia un nuevo objeto Minion. Siempre que un objeto NastyBoss tiene problemas (a través del método
// NastyBoss::projectFails()), busca un Minion para activar.
// Al instanciar un objeto Minion directamente en la clase NastyBoss, limitamos la flexibilidad. Si
// un objeto NastyBoss pudiera funcionar con cualquier instancia del tipo Employee, podríamos hacer que
// nuestro código sea susceptible de variación en tiempo de ejecución a medida que agregamos más especializaciones de Employee.
// Debería resultarle familiar el polimorfismo de la Figura 9-1.

// Si la clase NastyBoss no instancia un objeto Minion, ¿de dónde proviene?
// Los autores a menudo evitan este problema restringiendo un tipo de argumento en una declaración de método y luego omitiendo
//  convenientemente mostrar la instanciación en cualquier otro contexto que no sea de prueba: