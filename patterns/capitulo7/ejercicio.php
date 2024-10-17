<?php
abstract class Lesson
{
    public const FIXED = 1;
    public const TIMED = 2;
    public function __construct(protected int $duration, private int
    $costtype = 1) {}
    public function cost(): int
    {
        switch ($this->costtype) {
            case self::TIMED:
                return (5 * $this->duration);
                break;
            case self::FIXED:
                return 30;
                break;
            default:
                $this->costtype = self::FIXED;
                return 30;
        }
    }
    public function chargeType(): string
    {
        switch ($this->costtype) {
            case self::TIMED:
                return "hourly rate";
                break;
            case self::FIXED:
                return "fixed rate";
                break;
            default:
                $this->costtype = self::FIXED;
                return "fixed rate";
        }
    }
    // more lesson methods...
}
// listing 08.02
class Lecture extends Lesson
{
    // Lecture-specific implementations ...
}
// listing 08.03
class Seminar extends Lesson
{
    // Seminar-specific implementations ...
}
// listing 08.04
$lecture = new Lecture(5, Lesson::FIXED);
print "{$lecture->cost()} ({$lecture->chargeType()})\n";
$seminar = new Seminar(3, Lesson::TIMED);
print "{$seminar->cost()} ({$seminar->chargeType()})\n";
// He hecho que la estructura de clases sea mucho más manejable, pero con un costo. El uso de
// condicionales en este código es un paso atrás. Por lo general, intentaría reemplazar una
// declaración condicional con polimorfismo. Aquí, he hecho lo opuesto. Como puede ver, esto me ha obligado a duplicar la declaración condicional en los métodos chargeType()
// y cost(). Parece que estoy condenado a duplicar el código.


// Uso de la composición
// Puedo usar el patrón Estrategia para componer mi salida de los problemas. La estrategia se usa para mover
// un conjunto de algoritmos a un tipo separado. Al mover los cálculos de costos, puedo simplificar el
// tipo Lección. Puede ver esto en la Figura 8-4.