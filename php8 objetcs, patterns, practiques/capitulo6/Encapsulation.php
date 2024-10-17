<?php
// La encapsulación simplemente significa ocultar datos y funcionalidades a un cliente. Y, una vez más,
// es un concepto clave orientado a objetos.
// En el nivel más simple, encapsulas los datos declarando propiedades privadas o
// protegidas. Al ocultar una propiedad del código del cliente, haces cumplir una interfaz y evitas
// la corrupción accidental de los datos de un objeto.
// El polimorfismo ilustra otro tipo de encapsulación. Al colocar diferentes
// implementaciones detrás de una interfaz común, ocultas estas estrategias subyacentes
// al cliente. Esto significa que cualquier cambio que se haga detrás de esta interfaz es
// transparente para el sistema más amplio. Puedes agregar nuevas clases o cambiar el código en una clase
// sin causar errores. Lo que importa es la interfaz, no los mecanismos que funcionan
// debajo de ella. Cuanto más independientes se mantengan estos mecanismos, menos posibilidades habrá de que
// los cambios o reparaciones tengan un efecto dominó en tus proyectos.
// La encapsulación es, en cierto modo, la clave de la programación orientada a objetos. Tu
// objetivo debería ser hacer que cada parte sea lo más independiente posible de sus pares. Las clases
// y los métodos deberían recibir tanta información como sea necesaria para realizar sus tareas asignadas, que deberían tener un alcance limitado y estar claramente identificadas.
// La introducción de las palabras claves private, protected y public ha facilitado
// la encapsulación. Sin embargo, la encapsulación también es un estado mental. PHP 4 no proporcionaba soporte formal para
//  ocultar datos. La privacidad debía indicarse mediante documentación y convenciones de nombres. 
// Un underscore (guion bajo), por ejemplo, es una forma común de indicar una propiedad privada:
$_touchezpas;
// Por supuesto, el código debía revisarse minuciosamente, porque la privacidad no se aplicaba estrictamente.
// Sin embargo, es interesante que los errores fueran poco frecuentes porque la estructura y el estilo del código dejaban bastante claro qué propiedades no se querían modificar.
// De la misma manera, incluso después de la llegada de PHP 5, podíamos romper las reglas y descubrir
// el subtipo exacto de un objeto que estábamos usando en un contexto de cambio de clase simplemente
// usando el operador instanceof:

function workWithProducts(ShopProduct $prod)
{
    if ($prod instanceof CdProduct) {
        // do cd thing
    } elseif ($prod instanceof BookProduct) {
        // do book thing
    }
}
// Puede tener una muy buena razón para hacer esto, pero, en general, conlleva un olor ligeramente
// incierto. Al consultar el subtipo específico en el ejemplo, estoy estableciendo una
// dependencia. Aunque los detalles del subtipo estaban ocultos por el polimorfismo,
// habría sido posible haber cambiado la jerarquía de herencia de ShopProduct
// por completo sin efectos nocivos. Este código pone fin a eso. Ahora, si necesito racionalizar las clases
// CdProduct y BookProduct, puedo crear efectos secundarios inesperados en el método
// workWithProducts().
// Hay dos lecciones que sacar de este ejemplo. Primero, la encapsulación lo ayuda
// a crear código ortogonal. Segundo, el grado en que la encapsulación es exigible
// no viene al caso. La encapsulación es una técnica que deben observar por igual las clases y sus clientes.

// Olvídate de cómo hacerlo
// Si eres como yo, la mención de un problema hará que tu mente se acelere, buscando
// mecanismos que puedan proporcionar una solución. Puedes seleccionar funciones que aborden un problema, revisar expresiones regulares ingeniosas y rastrear paquetes de Composer. Probablemente tengas algún código pegable en un proyecto antiguo que haga algo similar. En la etapa de diseño, puedes beneficiarte de dejar todo eso de lado por un tiempo. Vacía tu
// cabeza de procedimientos y mecanismos. Piensa solo en los participantes clave de tu sistema: los tipos que necesitará y
// sus interfaces. Por supuesto, tu conocimiento del proceso informará tu pensamiento. Una
// clase que abre un archivo necesitará una ruta, el código de base de datos necesitará administrar nombres de tabla
// y contraseñas, etc. Sin embargo, deja que las estructuras y relaciones en tu código te guíen. Descubrirás que la implementación encaja fácilmente detrás de una interfaz bien definida. Entonces tienes la flexibilidad de cambiar, mejorar o extender una
// implementación si lo necesitas, sin afectar el sistema más amplio.
// Para enfatizar la interfaz, piense en términos de clases base abstractas o interfaces
// en lugar de elementos secundarios concretos. En mi código de obtención de parámetros, por ejemplo, la interfaz
// es el aspecto más importante del diseño. Quiero un tipo que lea y escriba pares nombre/valor. Es esta responsabilidad lo que es importante sobre el tipo, no el medio de persistencia real
// o los medios de almacenamiento y recuperación de datos. Diseño el sistema en torno a la clase abstracta ParamHandler y solo agrego las estrategias concretas para leer y escribir parámetros reales más adelante. De esta manera, incorporo tanto el polimorfismo como la encapsulación en mi sistema desde el principio. La estructura se presta al cambio de clases.

// Dicho esto, por supuesto, sabía desde el principio que habría implementaciones de ParamHandler en texto y XML, y no hay duda de que esto influyó en mi
// interfaz. Siempre hay que hacer una cierta cantidad de malabarismos mentales al diseñar
// interfaces. En Design Patterns: Elements of Reutilizable Object-Oriented Software (Addison-Wesley
// Professional, 1995), la Banda de los Cuatro resumió este principio con la frase:
// “Programa para una interfaz, no para una implementación”. Es una buena frase para añadir a tu manual de programación.

// Cuatro señales
// Muy pocas personas lo hacen absolutamente bien en la etapa de diseño. La mayoría de nosotros modificamos nuestro código a medida que cambian
// los requisitos o cuando adquirimos una comprensión más profunda de la naturaleza del problema
// que estamos abordando.
// A medida que modifica su código, puede fácilmente escaparse de su control. Se agrega un método
// aquí y una nueva clase allá, y gradualmente su sistema comienza a decaer. Como ya ha visto,
// su código puede señalar el camino hacia su propia mejora. Estos indicadores en el código a veces se denominan olores de código, es decir, características en el código que pueden sugerir
// correcciones particulares o al menos llamarlo a mirar nuevamente su diseño. En esta sección, destilo
// algunos de los puntos ya mencionados en cuatro señales a las que debe prestar atención mientras
// codifica.


// Duplicación de código
// La duplicación es uno de los grandes males del código. Si tiene una extraña sensación de déjà vu mientras
// escribe una rutina, es probable que tenga un problema.
// Observe las instancias de repetición en su sistema. Tal vez pertenezcan
// juntos. La duplicación generalmente significa un acoplamiento estrecho. Si cambias algo
// fundamental en una rutina, ¿será necesario modificar las rutinas similares? Si este es el
// caso, probablemente pertenezcan a la misma clase.

// La clase que sabía demasiado - The Class Who Knew Too Much
// Puede ser un fastidio pasar parámetros de un método a otro. ¿Por qué no simplemente
// reducir el problema utilizando una variable global? Con una variable global, todos pueden acceder a los datos.

// Las variables globales tienen su lugar, pero deben verse con cierto nivel de
// sospecha. Por cierto, es un nivel de sospecha bastante alto. Al usar una variable global,
// o al darle a una clase cualquier tipo de conocimiento sobre su dominio más amplio, la anclas a su
// contexto, haciéndola menos reutilizable y dependiente de código que está fuera de su control. Recuerda,
// quieres disociar tus clases y rutinas y no crear interdependencia. Intenta
// limitar el conocimiento de una clase sobre su contexto. Veré algunas estrategias para hacer esto más adelante en el libro.

// El hombre de todos los oficios The Jack of All Trades
// ¿Su clase está intentando hacer demasiadas cosas a la vez? Si es así, intente enumerar las
// responsabilidades de la clase. Es posible que una de ellas forme la base de una buena
// clase.
// Dejar una clase demasiado entusiasta sin cambios puede causar problemas particulares si crea
// subclases. ¿Qué responsabilidad está ampliando con la subclase? ¿Qué haría
// si necesitara una subclase para más de una responsabilidad? Es probable que termine
// con demasiadas subclases o con una dependencia excesiva del código condicional.

// Sentencias condicionales
// Utilizará las sentencias if y switch con toda razón en todos sus proyectos. A veces, sin embargo, estas estructuras pueden ser un llamado al polimorfismo.
// Si descubre que está probando ciertas condiciones con frecuencia dentro de una clase,
// especialmente si encuentra que estas pruebas se reflejan en más de un método, esto podría ser una señal de que su clase única debería ser dos o más. Vea si la estructura del código condicional sugiere responsabilidades que podrían expresarse en clases. Las nuevas clases deben implementar una clase base abstracta compartida. Es probable que luego
// tenga que averiguar cómo pasar la clase correcta al código del cliente. Trataré algunos patrones para crear objetos en el Capítulo 9.

// The UML
// Class Diagrams
// Table 6-1. Visibility Symbols
// Symbol Visibility Explanation
// + Public     Available to all code
// - Private    Available to the current class only
// # Protected  Available to the current class and its subclasses only

// HERENCIA- IMPLEMENTACIONES - ASOCIACIONES - Aggregation and Composition
// PAGINA 229 DDEL LIBRO EXPLICA GRAFICAMENTE EL FUNCIONAMIENTO
