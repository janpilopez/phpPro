<?php
// Has visto que el método __construct() se invoca automáticamente cuando se crea una instancia de un objeto. PHP 5 también introdujo el método __destruct(). Este se invoca justo
// antes de que un objeto sea recolectado como basura; es decir, antes de que se borre de la memoria. Puedes
// utilizar este método para realizar cualquier limpieza final que pueda ser necesaria
// Imaginemos, por ejemplo, una clase que se guarda a sí misma en una base de datos cuando se le ordena. Podría
// utilizar el método __destruct() para asegurar que una instancia guarde sus datos cuando se elimine:

class Person
{
    private int $id;
    public function __construct(protected string $name, private int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function __destruct()
    {
        if (!  empty($this->id)) {//no esta vacio?
            // save Person data
            print "saving person\n";
        }
    }
}
// El método __destruct() se invoca siempre que se llama a la función unset() en un
// objeto o cuando no existen más referencias al objeto en el proceso. Por lo tanto, si creo y
// destruyo un objeto Persona, puedes ver cómo entra en juego el método __destruct()
$person = new Person("bob", 44);
$person->setId(343);
//no hemos guardado en la base de datos o en cualquie lugar la persona
unset($person);//procedemos a borrarla
//pero antes de eliminar el objeto entra la funcion __destruct por loque sirve para asegurarse de que se guarden los datos en este caso



// Aunque trucos como este son divertidos, vale la pena hacer una advertencia. __call(), __
// destruct() y sus colegas a veces se denominan métodos mágicos. Como sabrá
// si alguna vez ha leído una novela de fantasía, la magia no siempre es algo bueno. La magia es arbitraria
// e inesperada.

// La magia tuerce las reglas. La magia implica costos ocultos.
// En el caso de __destruct(), por ejemplo, puede terminar cargando a los clientes con
// sorpresas desagradables. Piense en la clase Person: realiza una escritura en la base de datos en
// su método __destruct(). Ahora imagine a un desarrollador novato que pone a prueba la clase Person sin hacer nada. No detecta el método __destruct() y se pone a crear
// instancias de un conjunto de objetos Person. Al pasar valores al constructor, asigna el sobrenombre secreto y ligeramente obsceno del CEO a la propiedad $name y luego establece $age en
// 150.

// Ejecuta su script de prueba varias veces, probando combinaciones coloridas de nombre y edad.
// A la mañana siguiente, su gerente le pide que entre a una sala de reuniones para explicar por qué
// la base de datos contiene datos insultantes de Person. ¿La moraleja? No confíes en la magia