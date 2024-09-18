<?php
// Por supuesto, puede declarar un método de rasgo como público, privado o protegido. Sin embargo, también puede
// cambiar este acceso desde dentro de la clase que utiliza el rasgo. Ya ha visto que
// el operador as se puede utilizar para crear un alias para el nombre de un método. Si utiliza un modificador de acceso en el
// lado derecho de este operador, cambiará el nivel de acceso del método en lugar de su nombre.
// Imagine, por ejemplo, que desea utilizar calculateTax() desde dentro de
// UtilityService, pero no ponerlo a disposición del código de implementación.
abstract class Service
{
    // service oriented stuff
}
trait PriceUtilities
{
    private $taxrate = 20;
    public function calculateTax(float $price): float
    {
        return ($this->taxrate / 100) * $price;
    }
}
class UtilityService extends Service
{
    use PriceUtilities {
        PriceUtilities::calculateTax as private;
        //aqui podemos cambiar el acceso de la funcion calculateTax para bloquearla desde la clase de uso para restringir acceso
        //y por ejemplo solo este disponible desde la misma llamadas internas no desde fuera

    }
    public function __construct(private float $price)
    {
    }
    public function getTaxRate(): float
    {
        return 20;
    }
    public function getFinalPrice(): float
    {
        //accedemos al metodo calculateTax desde la misma clase internamente que previamente declaramos como privada
        return $this->price + $this->calculateTax($this->price);
    }
}
$u = new UtilityService(100);
// print $u->calculateTax(5) . "\n";//error, no puede ser accedida directamente
print $u->getFinalPrice() ."";// si puede ser accedida desde la misma clase internamente a traves de sus propios metodos