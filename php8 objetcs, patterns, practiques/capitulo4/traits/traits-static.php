<?php
trait PriceUtilitiesV1
{
    private static int $taxrate = 20;
    public static function calculateTax(float $price): float
    {
        return (self::$taxrate / 100) * $price;
    }
    // other utilities
}
abstract class Service
{
    // service oriented stuff
}
class UtilityServiceV1 extends Service
{
    use PriceUtilitiesV1;
}
print UtilityServiceV1::calculateTax(100) . "\n";
// Ahora debo llamar al método en la clase en lugar de en un objeto. Como podría esperarse, este script genera lo siguiente:
// 20
// Por lo tanto, los métodos estáticos se declaran en traits y se accede a ellos a través de la clase host de la manera habitual
trait PriceUtilities
{
    public function calculateTax(float $price): float
    {
        // is this good design?
        return ($this->taxrate / 100) * $price;
    }
    // other utilities
}
class UtilityService extends Service
{
    use PriceUtilities;
    public $taxrate = 20;
}