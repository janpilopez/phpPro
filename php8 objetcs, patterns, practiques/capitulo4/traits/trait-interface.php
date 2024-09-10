<?php

//EN OTRAS PALABRAS UN TRAIT ES UNA IMPLEMENTACION CONCRETA a una clase(CODIGO A REALIZAR, FUNCION , ETC REUTILIZABLE), QUE PUEDE
//que cumple con la interfaz y al usar el trait ya implementa este metodo obligatorio de la interfaz,
//ASI MISMO LE UBICAMOS RESTRICCION A LA CLASE CON EL TIPO IdentityObject, y solo estos objetos pueden ser usados con la funciones del trait

//A CONTINUACION


// Qué Significa "Aceptar Objetos IdentityObject"

// En el ejemplo proporcionado, el método storeIdentityObject está definido para aceptar un parámetro que debe ser 
//una instancia de una clase que implementa la interfaz IdentityObject. Esto se hace mediante la tipificación de parámetros en PHP.
// Ejemplo Detallado

// Vamos a repasar el flujo completo:
// Definición de la Interfaz:
// La interfaz IdentityObject define el contrato que cualquier clase debe seguir si quiere cumplir 
//con este tipo. En este caso, el contrato es tener un método generateId() que devuelve una cadena de texto.

interface IdentityObject
{
    public function generateId(): string;
}

// Definición del Trait:
// El trait IdentityTrait proporciona una implementación concreta del método generateId().

trait IdentityTrait
{
    public function generateId(): string
    {
        return uniqid(); // Genera un identificador único
    }
}

// Definición de la Clase:
// La clase ShopProduct implementa la interfaz IdentityObject y usa el trait IdentityTrait. Esto significa que ShopProduct
// cumple con el contrato de la interfaz IdentityObject, ya que implementa el método generateId() como se requiere.
class ShopProduct implements IdentityObject
{
    use IdentityTrait;
    // Otros métodos y propiedades específicos de ShopProduct
}

// Uso del Método con Tipificación:

// El método storeIdentityObject está tipificado para aceptar solo objetos que implementen IdentityObject. 
//En este caso, como ShopProduct implementa IdentityObject, puedes pasarle instancias de ShopProduct a este método.
//esto puede estar en un archivo aparte u otra clase
public static function storeIdentityObject(IdentityObject $idobj)
{
    // Puedes llamar al método generateId() garantizado por la interfaz
    $id = $idobj->generateId();
    echo "ID: $id";
}

// ¿Qué Significa Esto en la Práctica?
// Restricción de Tipo: El método storeIdentityObject no acepta cualquier objeto, solo aquellos que implementan la interfaz IdentityObject. 
//Esto asegura que cualquier objeto pasado a este método tendrá el método generateId() disponible.
// Flexibilidad y Reutilización: Aunque ShopProduct es una implementación concreta, cualquier otra clase que implemente IdentityObject puede 
//ser pasada a storeIdentityObject. Esto permite reutilizar el código en diferentes contextos siempre que las clases cumplan con el contrato 
//de la interfaz.
// Garantía de Métodos: Gracias a la tipificación con la interfaz, puedes confiar en que el método generateId() estará disponible para 
//cualquier objeto pasado al método storeIdentityObject.

// Ejemplo de Uso:
$product = new ShopProduct();
storeIdentityObject($product); // Esto funciona porque ShopProduct implementa IdentityObject

// También puedes tener otra clase que implemente IdentityObject
class AnotherProduct implements IdentityObject
{
    use IdentityTrait;
}

$anotherProduct = new AnotherProduct();
storeIdentityObject($anotherProduct); // También funciona aquí

// En resumen, al tipificar con IdentityObject, garantizas que los objetos pasados a tus métodos cumplen con el contrato definido por la interfaz, proporcionando una forma segura y flexible de manejar diferentes clases que implementen ese contrato.