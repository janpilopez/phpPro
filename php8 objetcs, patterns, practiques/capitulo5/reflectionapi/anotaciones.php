<?php
#[info]
class Person
{
    public $name;
    // #[moreinfo]
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}


// Atributos
// Muchos lenguajes proporcionan un mecanismo por el cual las etiquetas especiales en los archivos fuente pueden estar
// disponibles para el código. Estas a menudo se conocen como anotaciones. Aunque ha habido
// algunas implementaciones de espacio de usuario en paquetes PHP (notablemente, por ejemplo, la biblioteca de bases de datos Doctrine
// Capítulo 5 Herramientas de objetos 203 y el componente de enrutamiento Symfony) hasta PHP 8, no había soporte para esta
// función a nivel de lenguaje. Esto ha cambiado con la introducción de los atributos.
// Esencialmente, un atributo es una etiqueta especial que le permite agregar información adicional
// a una clase, método, propiedad, parámetro o constante. Esta información se vuelve disponible
// para un sistema a través de la reflexión.
// Entonces, ¿para qué puede usar las anotaciones? Por lo general, un método puede proporcionar más
// información sobre la forma en que espera ser utilizado. El código del cliente puede escanear una clase para
// descubrir métodos que deberían ejecutarse automáticamente, por ejemplo. Mencionaré otros casos de uso a medida que avancemos.
// Declaremos y accedamos a una anotación:

// namespace popp\ch05\batch09;
// #[info]

// #### Por lo tanto, una anotación se declara con un token de cadena encerrado entre #[ y ]. En este caso,
// he optado por #[info]. En muchos ejemplos de código, excluyo una declaración de espacio de nombres
// porque el código se ejecutará igualmente bien dentro de un espacio de nombres declarado o principal. En este caso,
// sin embargo, vale la pena señalar el espacio de nombres. Volveré a este punto.
// Ahora, para acceder a la anotación:
$rpers = new \ReflectionClass(Person::class);
$attrs = $rpers->getAttributes();
foreach ($attrs as $attr) {
    print $attr->getName() . "\n";
}
// Instancio un objeto ReflectionClass para poder examinar Person. Luego llamo al método
// getAttributes(). Esto devuelve una matriz de objetos ReflectionAttribute. Reflect
// ctionAttribute::getName() devuelve el nombre del atributo que declaré.
// Aquí está el resultado:
// popp\ch05\batch09\info
// Capítulo 5 Herramientas de objetos
// 204
// Entonces, en mi resultado, la anotación tiene un espacio de nombres. La parte popp\ch05\batch09
// del nombre es implícita. Puedo hacer referencia a una anotación de acuerdo con las mismas reglas y alias que usamos para hacer referencia a una clase. Por lo tanto, declarar [#info] dentro del espacio de nombres popp\ch05\batch09
// es equivalente a declarar [#\popp\ch05\batch09\info] en otro lugar. De hecho,
// como verá, incluso puede declarar una clase que se puede instanciar para cualquier atributo al que haga referencia.
// Las anotaciones se pueden aplicar a varios aspectos de PHP. La Tabla 5-2 enumera las características que se pueden anotar junto con las clases de Reflection correspondientes
// Feature             Acquisition
// Class    ReflectionClass::getAttributes()
// Property     ReflectionProperty::getAttributes()
// Function/Method  ReflectionFunction::getAttributes()
// Constant     ReflectionConstant::getAttributes()


// Now to access it. You should find the process pretty familiar:
$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setName");
$attrs = $rmeth->getAttributes();
foreach ($attrs as $attr) {
    print $attr->getName() . "\n";
}
