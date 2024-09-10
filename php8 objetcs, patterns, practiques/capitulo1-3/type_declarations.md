Type Declaration Since Description
array 5.1             An array. Can default to null or an array
int 7.0               An integer. Can default to null or an integer
float 7.0             A floating-point number (a number with a decimal point). An integer will be accepted—even with strict mode enabled. Can default to null, a float, or an integer 
callable 5.4         Callable code (such as an anonymous function). Can default to null 
bool 7.0             A Boolean. Can default to null or a Boolean
string 5.0           Character data. Can default to null or a string
self 5.0             A reference to the containing class
[a class type] 5.0  The type of a class or interface. Can default to null
iterable 7.1        Can be traversed with foreach (not necessarily an array—could implement Traversable)
object 7.2          An object
mixed 8.0           Explicit notification that the value can be of any type

UNION TYPES !! TIPOS DE UNION
//BEFORE
public function add(string $key, $value)
{
    if (! is_bool($value) && ! is_string($value)) {
        error_log("value must be string or Boolean - given: " .
        gettype($value));
        return false;
    }
}

//AFTER Union types were added in PHP 8.
public function add(string $key, string|bool $value)
{
    // do something with $key and $value
    se generará un ahora conocido TypeError SI SE INGRESA UN VALOR NO ESPECIFICADO
}

public function setShopProduct(ShopProduct|null $product)
{
    // do something with $product
}
public function setShopProduct2(ShopProduct|false $product)
{
    // do something with $product
    //Esto es más útil que la unión ShopProduct|bool porque no quiero aceptar verdadero en ningún escenario, ya que como vimos anterior cualquier cadena es considerado true
}


//NULLABLE TYPES
public function add(string $key, ?string $value)
{
    // do something with $key and $value
    //ACEPTA NULLOS O CADENAS, ALTERNATIVA A ESTABLECER string|null
}


//Return Type Declarations
//SIRVE PARA DECLARAR EL TIPO DE DATO QUE DEBE RETORNAR NUESTRA FUNCION
public function getPlayLength(): int
{
    return $this->playLength;
}

public function getPrice(): int|float
{
 return ($this->price - $this->discount);
}

//TAMBIEN PODEMOS NO RETORNAR NADA YA QUE NO QUEREMOS UN VALOR DE RETORNO MAS BIEN SOLO PROPORCIONAR O ASIGNAR UN VALOR
public function setDiscount(int|float $num): void
{
 $this->discount = $num;
}