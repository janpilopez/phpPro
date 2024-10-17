<?php
abstract class Lesson
{
    public function __construct(private int $duration, private CostStrategy $costStrategy) {}
    public function cost(): int
    {
        return $this->costStrategy->cost($this);
    }
    public function chargeType(): string
    {
        return $this->costStrategy->chargeType();
    }
    public function getDuration(): int
    {
        return $this->duration;
    }
    // more lesson methods...

}
// listing 08.06
class Lecture extends Lesson
{
    // Lecture-specific implementations ...
}
// listing 08.07
class Seminar extends Lesson
{
    // Seminar-specific implementations ...
}
// La clase Lesson requiere un objeto CostStrategy, que almacena como una propiedad.
// El método Lesson::cost() simplemente invoca CostStrategy::cost(). De igual forma,
// Lesson::chargeType() invoca CostStrategy::chargeType(). Esta invocación explícita
// del método de otro objeto para cumplir con una solicitud se conoce como delegación. En mi
// ejemplo, el objeto CostStrategy es el delegado de Lesson. La clase Lesson se desentiende
// de la responsabilidad de los cálculos de costos y pasa la tarea a una implementación de CostStrategy.
//  Aquí, se la descubre en el acto de delegación:
abstract class CostStrategy
{
    abstract public function cost(Lesson $lesson): int;
    abstract public function chargeType(): string;
}
// listing 08.10
class TimedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson): int
    {
        return ($lesson->getDuration() * 5);
    }
    public function chargeType(): string
    {
        return "hourly rate";
    }
}
// listing 08.11
class FixedCostStrategy extends CostStrategy
{
    public function cost(Lesson $lesson): int
    {
        return 30;
    }
    public function chargeType(): string
    {
        return "fixed rate";
    }
}
