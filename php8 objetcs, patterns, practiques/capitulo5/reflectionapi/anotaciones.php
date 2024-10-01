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

// Feature              Acquisition
// Class                ReflectionClass::getAttributes()
// Property             ReflectionProperty::getAttributes()
// Function/Method      ReflectionFunction::getAttributes()
// Constant             ReflectionConstant::getAttributes()


// Now to access it. You should find the process pretty familiar:
$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setName");
$attrs = $rmeth->getAttributes();
foreach ($attrs as $attr) {
    print $attr->getName() . "\n";
}
// Instanciaré un objeto ReflectionClass para poder examinar Person. Luego, llamo al método
// getAttributes(). Esto devuelve una matriz de objetos ReflectionAttribute. Reflect
// ctionAttribute::getName() devuelve el nombre del atributo que declaré.
// Este es el resultado: info

// Ahora, la salida también debería resultar familiar. Mostramos una ruta con espacio de nombres completo a
// moreinfo.
// popp\ch05\batch09\moreinfo
// Lo que has visto hasta ahora ya tiene algún uso. Podemos incluir un atributo
// como un indicador de algún tipo. Por ejemplo, un atributo Debug podría estar asociado con métodos
// que solo deberían invocarse durante el desarrollo. Sin embargo, los atributos son mucho más. Podemos definir un tipo y proporcionar más información a través de argumentos.
// Esto abre nuevas posibilidades. En una biblioteca de enrutamiento, podría afirmar el punto final de URL
// al que debería asignarse un método. En un sistema de eventos, un atributo podría indicar que una clase o
// método debería estar asociado con un evento en particular.
// En este ejemplo, defino un atributo que incluye dos argumentos:
#[ApiInfo("The 3 digit company identifier", "A five character department tag")]

public function setInfo(int $companyid, string $department): void
{
 $this->companyid = $companyid;
 $this->department = $department;
}
// Una vez que he adquirido un objeto ReflectionAttribute, puedo acceder a los argumentos
// usando el método getArguments().

$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes();
foreach ($attrs as $attr) {
 print $attr->getName() . "\n";
 foreach ($attr->getArguments() as $arg) {
 print " - $arg\n";
 }
//  Aquí está el resultado:
//  popp\ch05\batch09\ApiInfo
//  - El identificador de la empresa de 3 dígitos
//  - Una etiqueta de departamento de cinco caracteres

// Como mencioné, puedes asignar explícitamente un atributo a una clase. Aquí hay una clase ApiInfo simple:
namespace popp\ch05\batch09;
use Attribute;
#[Attribute]
class ApiInfo
{
 public function __construct(public string $compinfo, public string
$depinfo)
 {
 }
}

// Para realizar correctamente la asociación entre el atributo y mi clase, debo
// recordar usar Attribute y también aplicar el atributo integrado [#Attribute] a la clase.
// En el momento de la instanciación, todos los argumentos del atributo asociado se pasan
// automáticamente al constructor de la clase correspondiente. En este caso, simplemente asigno los datos a las propiedades correspondientes. En una aplicación del mundo real, probablemente
// realizaría algún procesamiento adicional o proporcionaría una funcionalidad asociada para justificar la declaración de una clase.
// Es importante entender que la clase de atributo no se invoca automáticamente. Debemos
// hacerlo a través de ReflectionAttribute::newInstance(). Aquí, adapto mi código de cliente para que funcione con la nueva clase:

// listing 05.91
$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes();
foreach ($attrs as $attr) {
    print $attr->getName() . "\n";
    $attrobj = $attr->newInstance();
    print " - " . $attrobj->compinfo . "\n";
    print " - " . $attrobj->depinfo . "\n";
}
// Aunque accedo a los datos de atributos a través del objeto ApiInfo, el efecto
// aquí es idéntico. Llamo a ReflectionAttribute::newInstance() y luego accedo a las
// propiedades completadas.
// ¡Un momento! Ese último ejemplo tiene un defecto profundo y potencialmente fatal. Se pueden agregar múltiples
// atributos a un método. Por lo tanto, no podemos estar seguros de que cada atributo
// asignado al método setInfo() sea una instancia de ApiInfo. Esos accesos a propiedades
// a ApiInfo::$compinfo y ApiInfo::$depinfo están destinados a fallar para cualquier atributo que no sea
// del tipo ApiInfo.
// Afortunadamente, podemos aplicar un filtro a getAttributes():
$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes(ApiInfo::class);
// Now, only exact matches for ApiInfo::class will be returned—rendering the rest of
// the code safe. We could relax things a little further like this:
$rpers = new \ReflectionClass(Person::class);
$rmeth = $rpers->getMethod("setInfo");
$attrs = $rmeth->getAttributes(ApiInfo::class, \ReflectionAttribute::IS_INSTANCEOF);
// Al pasar un segundo parámetro, ReflectionAttribute::IS_INSTANCEOF, a Re
// flectionAttribute::getAttributes(), aflojo el filtro para que coincida con la clase especificada
// y cualquier extensión o implementación de clases o interfaces secundarias.
// La Tabla 5-3 enumera los métodos de ReflectionAttribute que hemos encontrado
// getName()           Returns a fully namespaced type for the attribute
// getArguments()      Returns an array of all arguments associated with the referenced attribute
// newInstance()       Instantiates and returns an instance of the attribute class, having passed any arguments to the constructor