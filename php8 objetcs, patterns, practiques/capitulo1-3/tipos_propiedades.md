class Point
{
 private $x = 0;
 private $y = 0;
 public function setVals(int $x, int $y)
 {
 $this->x = $x;
 $this->y = $y;
 }
 public function getX(): int
 {
 return $this->x;
 }
 public function getY(): int
 {
 return $this->y;
 }
}

<!-- We were stuck with this method of fixing the types of properties up until PHP -->
<!-- version 7.4 which introduced typed properties. This allows us to declare types for our -->
<!-- properties. Here is a version of Point that takes advantage of this: -->
class Point
{
 public int $x = 0;
 public int $y = 0;
}
<!-- He hecho públicas las propiedades $x y $y y he utilizado la declaración de tipos para restringir -->
<!-- sus tipos. Debido a esto, puedo elegir, si quiero, deshacerme del método setVals() -->
<!-- sin sacrificar el control. Tampoco necesito ya los métodos getX() y getY(). Point -->
<!-- es ahora una clase excepcionalmente simple, pero, incluso con ambas propiedades públicas, ofrece -->
<!-- garantías mundiales sobre los datos que contiene. -->