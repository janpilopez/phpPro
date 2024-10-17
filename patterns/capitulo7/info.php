<?php
// ¿Qué son los patrones de diseño? ¿Por qué usarlos?

// La mayoría de los problemas que encontramos como programadores han sido resueltos una y otra vez por otros miembros de nuestra comunidad. Los patrones de diseño pueden brindarnos los medios para extraer esa sabiduría. Una vez que un patrón se convierte en moneda común, enriquece nuestro lenguaje, lo que facilita compartir ideas de diseño y sus consecuencias. Los patrones de diseño simplemente destilan problemas comunes, definen soluciones probadas y describen resultados probables. Muchos libros y artículos se centran en los detalles de los lenguajes de computadora, como las funciones, clases y métodos disponibles, etc. Los catálogos de patrones se concentran en cambio en cómo puede pasar de estos conceptos básicos (el "qué") a una comprensión de los problemas y las posibles soluciones en sus proyectos (el "por qué" y el "cómo").
// En este capítulo, le presento los patrones de diseño y analizo algunas de las razones de su popularidad. Este capítulo cubrirá lo siguiente:
// • Conceptos básicos de patrones: ¿Qué son los patrones de diseño?
// • Estructura de patrones: ¿Cuáles son los elementos clave de un patrón de diseño?
// • Beneficios de los patrones: ¿Por qué vale la pena invertir tiempo en los patrones?

// ¿Qué son los patrones de diseño?
// ########## En el mundo del software, un patrón es una manifestación tangible de la memoria tribal de una organización.  —Grady Booch en Patrones básicos de J2EE
// ########## [Un patrón es] una solución a un problema en un contexto. —The Gang of Four, Design Patterns: Elements of Reusable ObjectOriented Software

// Como implican estas citas, un patrón de diseño proporciona un análisis de un problema particular
// y describe las buenas prácticas para su solución.
// Los problemas tienden a repetirse y, como programadores web, debemos resolverlos una y otra vez.
// ¿Cómo debemos manejar una solicitud entrante? ¿Cómo podemos traducir estos datos en
// instrucciones para nuestro sistema? ¿Cómo debemos adquirir datos? ¿Presentar resultados? Con el tiempo,
// respondemos estas preguntas con un mayor o menor grado de elegancia y desarrollamos un
// conjunto informal de técnicas que usamos y reutilizamos en nuestros proyectos. Estas técnicas son
// patrones de diseño.
// Los patrones de diseño inscriben y formalizan estos problemas y soluciones, poniendo a disposición de la comunidad de programación más amplia la experiencia adquirida con esfuerzo. Los patrones son (o deberían
// ser) esencialmente de abajo hacia arriba y no de arriba hacia abajo. Tienen sus raíces en la práctica y no en la teoría.
// Eso no quiere decir que no haya un fuerte elemento teórico en los patrones de diseño (como veremos en el próximo capítulo), pero los patrones se basan en técnicas del mundo real utilizadas por programadores reales. El famoso creador de patrones Martin Fowler dice que descubre
// patrones, no los inventa. Por este motivo, muchos patrones generarán una sensación
// de déjà vu al reconocer técnicas que usted mismo ha utilizado.
// Un catálogo de patrones no es un libro de cocina. Las recetas se pueden seguir al pie de la letra; el código se puede
// copiar y encajar en un proyecto con cambios menores. No siempre es necesario
// entender todo el código utilizado en una receta. Los patrones de diseño inscriben enfoques para
// problemas particulares. Los detalles de implementación pueden variar enormemente según el contexto más amplio. Este contexto puede incluir el lenguaje de programación que está utilizando,
// la naturaleza de su aplicación, el tamaño de su proyecto y los detalles del problema.
// Digamos, por ejemplo, que su proyecto requiere que cree un sistema de plantillas.
// Dado el nombre de un archivo de plantilla, debe analizarlo y construir un árbol de objetos para
// representar las etiquetas que encuentra.
// Comienza con un analizador predeterminado que escanea el texto en busca de tokens de activación. Cuando
// encuentra una coincidencia, transfiere la responsabilidad de la búsqueda a otro objeto analizador, que está
// especializado en leer los componentes internos de las etiquetas. Este continúa examinando los datos de la plantilla hasta que falla, termina o encuentra otro activador. Si encuentra un disparador, también debe
// delegar la responsabilidad a un especialista, tal vez un analizador de argumentos. En conjunto, estos
// componentes forman lo que se conoce como un analizador de descenso recursivo
// Así que estos son sus participantes: un MainParser, un TagParser y un ArgumentParser.
// Usted crea una clase ParserFactory para crear y devolver estos objetos.
// Por supuesto, nada es fácil, y se le informa más tarde en el juego que debe
// soportar más de una sintaxis en sus plantillas. Ahora, necesita crear un conjunto paralelo
// de analizadores de acuerdo con la sintaxis: un OtherTagParser, un OtherArgumentParser, y así sucesivamente.

// Este es su problema: necesita generar un conjunto diferente de objetos de acuerdo con la
// circunstancia, y desea que esto sea más o menos transparente para otros componentes
// en el sistema. Resulta que Gang of Four define el siguiente problema
// en la página de resumen de su libro para el patrón Abstract Factory, “Proporcionar una interfaz
// para crear familias de objetos relacionados o dependientes sin especificar sus clases concretas”.

// Eso encaja perfectamente. Es la naturaleza de nuestro problema lo que determina y da forma a nuestro uso de
// este patrón. Tampoco hay nada de cortar y pegar en la solución, como puedes ver en el
// Capítulo 9, en el que cubro Abstract Factory.

// El acto mismo de nombrar un patrón es valioso; contribuye al tipo de vocabulario común que ha surgido naturalmente a lo
//  largo de los años en los oficios y profesiones más antiguos.
// Esta abreviatura ayuda en gran medida al diseño colaborativo, ya que se sopesan y prueban los enfoques alternativos y sus diversas consecuencias. 
// Cuando discuta sus familias de analizadores alternativos, por ejemplo, puede simplemente decirles a sus colegas que el sistema crea cada conjunto
// de objetos utilizando el patrón Abstract Factory. Asentirán sabiamente, ya sea inmediatamente
// iluminados o haciendo una nota mental para buscarlo más tarde. El punto es que este conjunto de conceptos y consecuencias tiene un identificador,
//  lo que lo convierte en una abreviatura útil, como lo ilustraré más adelante en este capítulo.
// Por último, es ilegal, según el derecho internacional, escribir sobre patrones sin citar a Christopher Alexander, un académico de arquitectura cuyo 
// trabajo influyó en gran medida en los defensores originales de los patrones orientados a objetos. En A Pattern
// Language (Oxford University Press, 1977) afirma:
//############## Cada patrón describe un problema que se repite una y otra vez en nuestro
// entorno, y luego describe el núcleo de la solución a ese problema, de tal manera que se puede utilizar esta solución un millón de veces, sin tener 
// que hacerlo dos veces de la misma manera.
// Es significativo que esta definición (que se aplica a los problemas arquitectónicos y
// a las soluciones) comience con el problema y su contexto más amplio y luego avance hacia una solución.
// En los últimos años, ha habido algunas críticas de que se han usado excesivamente los patrones de diseño,
// especialmente por programadores inexpertos. Esto suele ser una señal de que se han
// aplicado soluciones donde el problema y el contexto no están presentes. Los patrones son más que una
// organización particular de clases y objetos que cooperan de una manera particular. Los patrones
// están estructurados para definir las condiciones en las que se deben aplicar las soluciones y para
// discutir los efectos de la solución.
// En este libro, me centraré en una rama particularmente influyente en el campo de los patrones: la
// forma descrita en Design Patterns: Elements of Reutilizable Object-Oriented Software de
// The Gang of Four (Addison-Wesley Professional, 1995). Se concentra en los patrones en el
// desarrollo de software orientado a objetos e inscribe algunos de los patrones clásicos que están
// presentes en la mayoría de los proyectos orientados a objetos modernos.
// El libro The Gang of Four es importante porque inscribe patrones clave y porque
// describe los principios de diseño que informan y motivan estos patrones. Veremos
// algunos de estos principios en el próximo capítulo.

// Nota: Los patrones descritos por la Banda de los Cuatro y en este libro son en realidad
// instancias de un lenguaje de patrones. Un lenguaje de patrones es un catálogo de problemas y
// soluciones organizados de manera que se complementen entre sí, formando un todo
// interrelacionado. Existen lenguajes de patrones para otros espacios de problemas, como el diseño visual y la gestión de proyectos (y la arquitectura,
//  por supuesto). Cuando
// analizo aquí los patrones de diseño, me refiero a problemas y soluciones en el desarrollo de software orientado a objetos.