<?php
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
class NastyBoss
{
    private array $employees = [];
    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }
    public function projectFails(): void
    {
        if (count($this->employees)) {
            $emp = array_pop($this->employees);
            $emp->fire();
        }
    }
}
class CluedUp extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll call my lawyer\n";
    }
}
$boss = new NastyBoss();
$boss->addEmployee(new Minion("harry"));
$boss->addEmployee(new CluedUp("bob"));
$boss->addEmployee(new Minion("mary"));
$boss->projectFails();
$boss->projectFails();
$boss->projectFails();

// Aunque esta versión de la clase NastyBoss funciona con el tipo Employee y, por lo tanto, se beneficia del polimorfismo,
//  todavía no he definido una estrategia para la creación de objetos. Crear instancias de objetos es un asunto sucio, 
//  pero hay que hacerlo. Este capítulo trata
// sobre clases y objetos que funcionan con clases concretas, de modo que el resto de las clases no tengan que hacerlo.
// Si hay un principio que se puede encontrar aquí, es el de “delegar la instanciación de objetos”. Lo hice
// implícitamente en el ejemplo anterior al exigir que se pasara un objeto Employee al
// método NastyBoss::addEmployee(). Sin embargo, también podría delegar en una clase o método independiente 
// que se haga responsable de generar objetos Employee. Aquí, agrego un
// método estático a la clase Employee que implementa una estrategia para la creación de objetos: