<?php
abstract class Employee
{
    private static $types = ['Minion', 'CluedUp', 'WellConnected'];

    public static function recruit(string $name): Employee
    {
        $num = rand(1, count(self::$types)) - 1;
        $class = __NAMESPACE__ . "\\" . self::$types[$num];
        echo $class . "\n";
        return new $class($name);
    }

    public function __construct(protected string $name) {}
    abstract public function fire(): void;
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
class Minion extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll clear my desk\n";
    }
}
class CluedUp extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll call my lawyer\n";
    }
}
class WellConnected extends Employee
{
    public function fire(): void
    {
        print "{$this->name}: I'll call my dad\n";
    }
}
// Como puede ver, esto toma una cadena de nombre y la usa para crear una instancia de un subtipo de empleado
// en particular al azar. Ahora puedo delegar los detalles de la instanciación al método recruit() de la clase
// Employee:
$boss = new NastyBoss();
$boss->addEmployee(Employee::recruit("harry"));
$boss->addEmployee(Employee::recruit(name: "bob"));
$boss->addEmployee(Employee::recruit("mary"));





###EXAMPLE ACLARACION
// function getInstance(int $id, \PDO $pdo): ShopProduct
// {
//     $stmt = $pdo->prepare("select * from products where id=?");
//     $result = $stmt->execute([$id]);
//     $row = $stmt->fetch();
//     if (empty($row)) {
//         return null;
//     }
//     if ($row['type'] == "book") {
//         // instantiate a BookProduct object
//     } elseif ($row['type'] == "cd") {
//         // instantiate a CdProduct object
//     } else {
//         // instantiate a ShopProduct object
//     }
//     // $product->setId((int) $row['id']);
//     // $product->setDiscount((int) $row['discount']);
//     // return $product;
// }

// El método getInstance() utiliza una declaración if/else grande para determinar qué
// subclase instanciar. Los condicionales como este son bastante comunes en el código de fábrica.
// Aunque debería intentar eliminar las declaraciones condicionales grandes de sus proyectos,
// hacerlo a menudo tiene el efecto de retrasar la condición al momento en el que se genera un
// objeto. Esto no suele ser un problema grave porque elimina las condicionales paralelas de su código 
// al retrasar la toma de decisiones a este punto.
// En este capítulo, entonces, examinaré algunos de los patrones clave de la Banda de los Cuatro para
// generar objetos.
