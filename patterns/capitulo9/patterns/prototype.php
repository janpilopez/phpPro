<?php
// Prototipo
// La aparición de jerarquías de herencia paralelas puede ser un problema con el patrón
// Factory Method. Este es un tipo de acoplamiento que hace que algunos programadores
// se sientan incómodos. Cada vez que agrega una familia de productos, se ve obligado a crear
// un creador concreto asociado (por ejemplo, los codificadores BloggsCal se corresponden con
// BloggsCommsManager). En un sistema que crece lo suficientemente rápido como para abarcar muchos productos,
// mantener este tipo de relación puede volverse rápidamente tedioso.
// Una forma de evitar esta dependencia es usar la palabra clave clone de PHP para duplicar los productos concretos existentes. 
// Las clases de productos concretos se convierten entonces en la
// base de su propia generación. Este es el patrón Prototipo. Le permite reemplazar la herencia por la composición. Esto, 
// a su vez, promueve la flexibilidad en tiempo de ejecución y reduce la
// cantidad de clases que debe crear.

// El problema
// Imagina un juego web al estilo Civilization en el que las unidades operan en una cuadrícula de casillas. Cada casilla
// puede representar mar, llanuras o bosques. El tipo de terreno restringe el movimiento
// y las habilidades de combate de las unidades que ocupan la casilla. Puedes tener un objeto TerrainFactory
// que sirva objetos Sea, Forest y Plains. Decide que permitirás al
// usuario elegir entre entornos radicalmente diferentes, por lo que el objeto Sea es una
// superclase abstracta implementada por MarsSea y EarthSea. Los objetos Forest y Plains
// se implementan de manera similar. Las fuerzas aquí se prestan al patrón Abstract Factory. 
// Tienes jerarquías de productos distintas (Sea, Plains, Forests), con fuertes relaciones familiares que atraviesan 
// la herencia (Earth, Mars). La Figura 9-10 presenta un diagrama de clases que muestra cómo puedes implementar 
// los patrones Abstract Factory y Factory Method para trabajar con estos productos.

// Implementación
// Cuando trabajas con los patrones Abstract Factory/Factory Method, debes decidir, en
// algún momento, qué creador concreto deseas utilizar, probablemente marcando algún tipo de
// marcador de preferencia. Como debes hacer esto de todos modos, ¿por qué no simplemente crear una clase de fábrica que
// almacene productos concretos y luego la completes durante la inicialización? De esta manera puedes reducir un par de clases y, como verás, aprovechar otros beneficios.
// A continuación, se incluye un código simple que utiliza el patrón Prototype en una fábrica:
class Plains {}
class Forest {}

// class Sea {} //OJO COMENTADA PRIMER EJERCICIOOO, PRIMERA PARTE


class EarthPlains extends Plains {}
class EarthSea extends Sea {}
// listing 09.37
class EarthForest extends Forest {}
// listing 09.38
class MarsSea extends Sea {}
// listing 09.39
class MarsForest extends Forest {}
// listing 09.40
class MarsPlains extends Plains {}
// listing 09.41
class TerrainFactory
{
    public function __construct(
        private Sea $sea,
        private Plains $plains,
        private Forest $forest
    ) {}
    public function getSea(): Sea
    {
        return clone $this->sea;
    }
    public function getPlains(): Plains
    {
        return clone $this->plains;
    }
    public function getForest(): Forest
    {
        return clone $this->forest;
    }
}
// listing 09.42
// $factory = new TerrainFactory(
//     new EarthSea(),
//     new EarthPlains(),
//     new EarthForest() // new MarsForest()

// );
print_r($factory->getSea());
print_r($factory->getPlains());
print_r($factory->getForest());

// Como puede ver, cargo un TerrainFactory concreto con instancias de objetos de producto. 
// Cuando un cliente llama a getSea(), devuelvo un clon del objeto Sea que almacené en caché durante la inicialización. 
// Esta estructura me brinda flexibilidad adicional. ¿Quiere jugar un juego en un nuevo planeta con mares y bosques como 
// los de la Tierra, pero llanuras como las de Marte? No es necesario escribir una 
// nueva clase creadora; simplemente puede cambiar la combinación de clases que agrega a TerrainFactory:*

// $factory = new TerrainFactory(
//     new EarthSea(),
//     new MarsPlains(),
//     new EarthForest()
// );

// Por lo tanto, el patrón Prototype le permite aprovechar la flexibilidad que ofrece
// la composición. Sin embargo, obtenemos más que eso. Debido a que está almacenando y clonando
// objetos en tiempo de ejecución, reproduce el estado del objeto cuando genera nuevos productos. Imagine
// que los objetos Sea tienen una propiedad $navigability. La propiedad influye en la cantidad de
// energía de movimiento que una pieza de mar absorbe de un barco y se puede configurar para ajustar el nivel de dificultad
// de un juego:
class Sea
{
    public function __construct(private int $navigability) {}
}
// Ahora, cuando inicializo el objeto TerrainFactory, puedo agregar un objeto Sea
// con un modificador de navegabilidad. Esto será válido para todos los objetos Sea atendidos por
// TerrainFactory:
$factory = new TerrainFactory(
    new EarthSea(-1),
    new EarthPlains(),
    new EarthForest()
);

// Nota: Traté la clonación de objetos en el Capítulo 4. La palabra clave clone genera una
// copia superficial de cualquier objeto al que se aplique. Esto significa que el objeto
// del producto tendrá las mismas propiedades que la fuente. Si alguna de las propiedades de la fuente
// son objetos, entonces no se copiarán en el producto. En cambio, el producto
// hará referencia a las mismas propiedades del objeto. Depende de usted cambiar este valor predeterminado
// y personalizar la copia de objetos de cualquier otra manera, implementando un método __clone(). Esto se 
// llama automáticamente cuando se usa la palabra clave clone.
class Contained {}
// listing 09.47
class Container
{
    public Contained $contained;
    public function __construct()
    {
        $this->contained = new Contained();
    }
    public function __clone()
    {
        // Ensure that cloned object holds a clone of self::$contained and not a reference to it
        //// Asegúrese de que el objeto clonado contenga un clon de self::$contained y no una referencia a él
        $this->contained = clone $this->contained;
    }
}
