<?php
// Method Description
// __get($property)                         Invoked when an undefined property is accessed
// __set($property, $value)                 Invoked when a value is assigned to an undefined property
// __isset($property)                       Invoked when isset() is called on an undefined property
// __unset($property)                       Invoked when unset() is called on an undefined property
// __call($method, $arg_array)              Invoked when an undefined nonstatic method is called
// __callStatic($method, // $arg_array)     Invoked when an undefined static method is called

#GET AND SET
class Person
{
    public function __get(string $property): mixed
    {
        $method = "get{$property}";
        if (method_exists($this, $method)) {
            return $this->$method();
        }
    }
    public function getName(): string
    {
        return 'Bob';
    }
    public function getAge(): int
    {
        return 44;
    }

    //si creamos estas funciones, PHP nos permitira acceder a las propiedades de la clase sin necesidad de declararlas
    //Por ejemplo podemos verificar con isset si una propiedad existe, si borramos esta funcion se declarara como nula,
    //con esta podemos personalizar los get, set, isset y demas funciones de una clase para que busquen internamente
    //en la clase e intenter resolver la propiedad(crearla) que se esta buscando
    public function __isset(string $property): bool
    {
        $method = "get{$property}";
        return method_exists($this, $method);
    }
}
// Cuando un cliente intenta acceder a una propiedad no definida, se invoca el método __get(). He implementado __get() para tomar el nombre de la propiedad y construir una nueva cadena, anteponiendo la palabra “get”. Paso esta cadena a una función llamada method_exists(),
// que acepta un objeto y un nombre de método y prueba la existencia del método. Si el método existe, lo invoco y paso su valor de retorno al cliente. Supongamos que el cliente
// solicita una propiedad $name:
$p = new Person();
print '1 ' . $p->name . "\n";//en envia name como property (parametro)

####ISSET
// Si el método no existe, no hago nada. La propiedad a la que el usuario intenta acceder se resolverá como nula.
// El método __isset() funciona de manera similar a __get(). Se invoca después de que el
// cliente llama a isset() en una propiedad no definida. Así es como podría extender Person:
$p = new Person();
if (isset($p->name)) {
    print $p->name;
}

##SET
// El método __set() se invoca cuando el código del cliente intenta asignar una propiedad
// indefinida. Se le pasan dos argumentos: el nombre de la propiedad y el valor que el cliente
// está intentando establecer. puede decidir cómo trabajar con estos argumentos
class Personv2
{
    private ?string $myname;
    private ?int $myage;
    public function __set(string $property, mixed $value): void
    {
        //creamos un metodo set solo el nombre que servira para verificar si existe tal metodo,
        ///y este metodo a su vez nos permitirá acceder a la propiedad si, la misma propiedad tuviera un nombre distinto
        $method = "set{$property}";
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }
    public function setName(?string $name): void
    {
        //si es coincidente entonces podemos ver que dentro de este metodo ya apuntamos a la propiedad que existe
        $this->myname = $name;
        if (!is_null($name)) {
            $this->myname = strtoupper($this->myname);
        }
    }
    public function setAge(?int $age): void
    {
        $this->myage = $age;
    }

    public function __unset(string $property): void
    {
        $method = "set{$property}";
        if (method_exists($this, $method)) {
            $this->$method(null);
        }
    }
}
// En este ejemplo, trabajo con métodos “setter” en lugar de “getters”. Si un usuario intenta asignar una propiedad no definida, se invoca el método __set() con el nombre de la propiedad
// y el valor asignado. Pruebo la existencia del método apropiado y lo invoco si existe. De esta manera, puedo filtrar el valor asignado.
$p = new Person();
$p->name = 'bob';//SE ENVIA NAME Y BOBO COMO PARAMETROS PROPERTY Y VALUE
//el codigo funciona correctamente asi la propiedad se llame en realidad myname, pero el metodo setname nos permite acceder a la propiedad de otra forma
echo "\nset: " . $p->name . '';

#############UNSET
// Como es de esperar, __unset() refleja __set(). Cuando se llama a unset() en una propiedad
// indefinida, se invoca __unset() con el nombre de la propiedad. Luego, puede
// hacer lo que quiera con la información. Este ejemplo pasa null a un método resuelto
// utilizando la misma técnica que vio que utilizaba __set():
// Resumen

// Intercepción: __call actúa como un interceptor para redirigir llamadas a métodos no definidos en PersonV3 hacia la clase PersonWriter.
// Delegación: Esto permite que PersonV3 delegue la lógica relacionada con la escritura de datos (nombre, edad) a PersonWriter, manteniendo la separación de responsabilidades.

########CALL method pag.131
class PersonWriter
{
    public function writeName(PersonV3 $p): void
    {
        print "\nwriteName: " . $p->getName() . "\n";
    }
    public function writeAge(PersonV3 $p): void
    {
        print $p->getAge() . "\n";
    }
}
class PersonV3
{
    public function __construct(private PersonWriter $writer)
    {
    }
    public function __call(string $method, array $args): mixed
    {
        //UNA VEZ AQUI DENTRO DELEGAMOS A WRITER Y ACCEDEMOS A SUS METODOS (PERSONWRITER)
        if (method_exists($this->writer, $method)) {
            return $this->writer->$method($this);
        }
    }
    public function getName(): string
    {
        return 'Bob';
    }
    public function getAge(): int
    {
        return 44;
    }
}

// En este caso, se invoca el método __call(). Busco un método llamado writeName() en mi objeto PersonWriter y lo invoco.
$person = new PersonV3(new PersonWriter());
$person->writeName(); //SI NO ENCUENTRA SE VA A CALL, EL CALL PORQUE NO ES ASIGNACION COMO SET O GET, ES DIRECTAMENTE UN METODO () QUE SE LLAMA
//Esto me ahorra tener que invocar manualmente el método delegado de esta manera:
// public function writeName(): void
// {
//  $this->writer->writeName($this);
// }



// Volveré a los temas de delegación y reflexión más adelante en el libro.
// Los métodos de interceptor __get() y __set() también se pueden utilizar para gestionar
// propiedades compuestas. Esto puede ser una comodidad para el programador cliente. Imagine, por
// ejemplo, una clase Address que gestiona un número de casa y un nombre de calle. En última instancia,
// los datos de este objeto se escribirán en campos de base de datos, por lo que la separación de número y calle tiene sentido. Pero si los números de casa y los nombres de calles se adquieren comúnmente en
// masas indiferenciadas, entonces es posible que desee ayudar al usuario de la clase. Aquí hay una clase que
// gestiona una propiedad compuesta, Address::$streetaddress:

class Address
{
    private string $number;
    private string $street;
    public function __construct(string $maybenumber, string $maybestreet = null)
    {
        if (is_null($maybestreet)) {
            $this->streetaddress = $maybenumber;//INTERCEPTOR SET, SE ENVIA STREETADDRESS Y MAYBENUMBER COMO PARAMETROS
        } else {
            $this->number = $maybenumber;
            $this->street = $maybestreet;
        }
    }
    public function __set(string $property, mixed $value): void
    {
        if ($property === 'streetaddress') {
            if (preg_match("/^(\d+.*?)[\s,]+(.+)$/", $value, $matches)) {//EXPRESION REGULAR PARA SEPARAR NUMERO Y CADENAS (CALLE)
                $this->number = $matches[1];
                $this->street = $matches[2];
            } else {
                throw new \Exception("unable to parse street address:'{$value}'");
            }
        }
    }
    //AHORA TODAS NUESTRAS PROPIEDADES SON PRIVADAS POR TANTO NO PODEMOS ACCEDER A ELLAS DESDE FUERA DE LA CLASE,
    //POR TANTO CREAMOS UN INTERCEPTOR GET de tipo propiedad streetsddress PARA OBTENER ESAS PROPIEDADES NUMBER Y STREET Y ENVIAR UNA SOLO DIRECCION
    //ENTONCES LAS PODEMOS GUARDAR JUNTAS O SEPARADAS PERO SOLO PODREMOS VERLAS JUNTAS
    public function __get(string $property): mixed
    {
        if ($property === 'streetaddress') {
            return $this->number . ' ' . $this->street;
        }
    }
}
$address = new Address('441b Bakers Street');
echo $address->streetaddress;
echo $address->number;//no se puede acceder porque son propiedades privadas
var_dump($address) ;