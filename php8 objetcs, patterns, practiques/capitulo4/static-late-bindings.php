<?php
// abstract class DomainObject
// {
//  public static function create(): DomainObject
//  {
//  return new self();
//  }
// }
// Ahora, si llamamos a create() en una de las clases secundarias, ya no deberíamos causar un
// error y obtendremos un objeto relacionado con la clase que llamamos y no con la clase que
// alberga create().

// Error: Cannot instantiate abstract class
abstract class DomainObjectV1
{
    public static function create(): DomainObjectV1
    {
        return new static();
    }
}
// listing 04.54
class UserV1 extends DomainObjectV1
{
}
// listing 04.55
class DocumentV1 extends DomainObjectV1
{
}
print_r(Document::create());
//////////////////////////////////////////////////////////////////////

abstract class DomainObject
{
    private string $group;
    public function __construct()
    {
        $this->group = static::getGroup();
    }
    public static function create(): DomainObject
    {
        return new static();//regresamos el mismo objeto que se esta creando, osea instanciamos el constructor de la clase que se esta creando
    }
    public static function getGroup(): string
    {
        return 'default';
    }
}

class User extends DomainObject//Existente directamente de la clase abstracta
{
}

class Document extends DomainObject //Existente directamente de la clase abstracta
{
    public static function getGroup(): string
    {
        return 'document';
    }
}

class SpreadSheet extends Document//extiende de Document
{
}
print_r(User::create());
print_r(Document::create());

print_r(SpreadSheet::create());
// Para la clase User, no es necesario hacer mucho trabajo inteligente. El constructor DomainObject
// llama a getGroup() y lo encuentra localmente. En el caso de SpreadSheet, sin embargo, la búsqueda
// comienza en la clase invocada, SpreadSheet en sí. No proporciona ninguna implementación, por lo que se invoca el
// método getGroup() en la clase Document. Antes de PHP 5.3 y la vinculación estática tardía, me habría quedado atascado con la palabra clave self aquí, que solo buscaría
// getGroup() en la clase DomainObject