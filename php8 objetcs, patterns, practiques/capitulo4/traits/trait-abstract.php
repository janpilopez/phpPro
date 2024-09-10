<?php
trait PriceUtilities
{
    public function calculateTax(float $price): float
    {
        // better design.. we know getTaxRate() is implemented
        return ($this->getTaxRate() / 100) * $price;
    }
    abstract public function getTaxRate(): float;
    // other utilities
}
// Al declarar un método abstracto getTaxRate() en el rasgo PriceUtilities, fuerzo a la clase UtilityService a proporcionar una implementación.
class UtilityService extends Service
{
    use PriceUtilities;
    public function getTaxRate(): float
    {
        return 20;
    }
}
//CON ESTO CONSEGUIMOS OBLIGAR LA IMPLEMENTACION DE VALORES PARA LOS TRAIT AL MOMENTO DE SU USO 
//Y EVITAR ERROROES POR PROPIEDADES O METODOS NO ASIGNADAS