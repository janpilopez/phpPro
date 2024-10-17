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
//instanciamos las clases extendidas de la clase abstracta CostStrategy: por tanto TimedCostStrategy o FixedCostStrategy,
//que es el objeto que se le pasa a la clase Lesson como parametro en el constructor obligatorio
$lessons[] = new Seminar(4, new TimedCostStrategy());
$lessons[] = new Lecture(4, new FixedCostStrategy());
foreach ($lessons as $lesson) {
    print "lesson charge {$lesson->cost()}. ";
    print "Charge type: {$lesson->chargeType()}\n";
}

// Como puede ver, un efecto de esta estructura es que he centrado las responsabilidades
// de mis clases. Los objetos CostStrategy son responsables únicamente de calcular el costo y
// los objetos Lesson administran los datos de la lección.
// Por lo tanto, la composición puede hacer que su código sea más flexible porque los objetos se pueden
// combinar para manejar tareas dinámicamente de muchas más maneras de las que puede anticipar en una
// jerarquía de herencia sola. Sin embargo, puede haber una penalización con respecto a la legibilidad.
// Como la composición tiende a generar más tipos, con relaciones que no son fijas
// con la misma previsibilidad que en las relaciones de herencia, puede ser un poco más
// difícil digerir las relaciones en un sistema.