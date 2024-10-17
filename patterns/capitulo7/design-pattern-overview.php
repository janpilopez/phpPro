<?
// Descripción general de un patrón de diseño
// En esencia, un patrón de diseño consta de cuatro partes: el nombre, el problema, la solución y
// las consecuencias.

// Nombre
// Los nombres son importantes. Enriquecen el lenguaje de los programadores; unas pocas palabras cortas pueden sustituir
// a problemas y soluciones bastante complejos. Deben equilibrar la brevedad y la descripción.
// The Gang of Four afirma: “Encontrar buenos nombres ha sido una de las partes más difíciles del
// desarrollo de nuestro catálogo”.
// Martin Fowler está de acuerdo: “Los nombres de patrones son cruciales, porque parte del propósito
// de los patrones es crear un vocabulario que permita a los desarrolladores comunicarse de manera más
// efectiva” (Patterns of Enterprise Application Architecture, Addison-Wesley
// Professional, 2002).
// En Patterns of Enterprise Application Architecture, Martin Fowler perfecciona un patrón de acceso a bases de datos que encontré por primera vez en Core J2EE Patterns de Deepak Alur, Dan Malks y
// John Crupi (Prentice Hall, 2001). Fowler define dos patrones que describen
// especializaciones del patrón anterior. La lógica de su enfoque es claramente correcta (uno de los
// nuevos patrones modela objetos de dominio, mientras que el otro modela tablas de bases de datos, una
// distinción que era vaga en el trabajo anterior). No obstante, me resultó difícil entrenarme
// para pensar en términos de los nuevos patrones. Había estado usando el nombre del original en sesiones de diseño y documentos durante tanto tiempo que se había convertido en parte de mi lenguaje.


// El problema
// No importa cuán elegante sea la solución (y algunas son realmente muy elegantes), el problema
// y su contexto son la base de un patrón. Reconocer un problema es más difícil que
// aplicar cualquiera de las soluciones en un catálogo de patrones. Esta es una razón por la que algunas
// soluciones de patrones pueden ser mal aplicadas o sobreutilizadas.
// Los patrones describen un espacio de problemas con gran cuidado. El problema se describe brevemente
// y luego se contextualiza, a menudo con un ejemplo típico y uno o más diagramas. Se
// desglosa en sus detalles, sus diversas manifestaciones. Se describen todas las señales de advertencia que podrían
// ayudar a identificar el problema.


// La solución
// La solución se resume inicialmente junto con el problema. También se describe
// en detalle, a menudo utilizando diagramas de interacción y de clases UML. El patrón generalmente incluye un
// ejemplo de código.
// Aunque se puede presentar el código, la solución nunca se corta y pega. El patrón
// describe un enfoque a un problema. Puede haber cientos de matices en su
// implementación. Piense en las instrucciones para sembrar un cultivo de alimentos. Si simplemente sigues un
// conjunto de pasos a ciegas, es probable que pases hambre cuando llegue el momento de la cosecha. Sería más útil
// un enfoque basado en patrones que cubra las diversas condiciones que pueden aplicarse. La solución básica al problema
// (hacer que tu cultivo crezca) siempre será la misma (preparar el suelo,
// plantar semillas, regar, cosechar el cultivo), pero los pasos reales que tomes dependerán de todo tipo de
// factores, como el tipo de suelo, tu ubicación, la orientación de tu terreno, las plagas locales,
// etc.
// Martin Fowler se refiere a las soluciones en patrones como "a medias". Es decir, el codificador debe
// quitar el concepto y terminarlo por sí mismo.

// Consecuencias
// Cada decisión de diseño que tomes tendrá consecuencias más amplias. Esto debería incluir, por supuesto, la
// resolución satisfactoria del problema en cuestión. Una vez implementada, una solución puede ser ideal para trabajar con otros patrones. También puede haber peligros a los que prestar atención.

// El formato de la Banda de los Cuatro
// Mientras escribo, tengo cinco catálogos de patrones en el escritorio frente a mí. Una mirada rápida a los
// patrones de cada uno confirma que ninguno de ellos usa la misma estructura. Algunos son formales;
// algunos son de grano fino, con muchas subsecciones; y otros son discursivos.
// Hay varias estructuras de patrones bien definidas, incluida la forma original
// desarrollada por Christopher Alexander (la forma alejandrina) y el enfoque narrativo
// favorecido por el Portland Pattern Repository (la forma Portland). Debido a que el libro de la Banda de los Cuatro es tan influyente, y debido a que cubriremos muchos de los patrones que describen,
// examinemos algunas de las secciones que incluyen en sus patrones:
// • Intención: Una breve declaración del propósito del patrón. Debería poder
// ver el objetivo del patrón de un vistazo.
// • Motivación: El problema descrito, a menudo en términos de una situación típica. El enfoque anecdótico puede ayudar a que el patrón sea fácil de entender.
// • Aplicabilidad: un análisis de las diferentes situaciones en las que
// se podría aplicar el patrón. Si bien la motivación describe un problema típico, esta sección define situaciones específicas y evalúa los
// méritos de la solución en el contexto de cada una.
// Estructura/interacción: Estas secciones pueden contener diagramas de interacción y de clases UML que describen las relaciones entre las clases y los
// objetos de la solución.
// • Implementación: Esta sección analiza los detalles de la solución. Examina los
// problemas que pueden surgir al aplicar la técnica y brinda consejos para la implementación.
// • Código de muestra: Siempre paso directamente a esta sección. Considero que un simple
// ejemplo de código a menudo proporciona una forma de acceder a un patrón. El ejemplo
// a menudo se reduce a lo básico para dejar al descubierto la solución. Puede estar en cualquier lenguaje orientado a objetos. Por supuesto, en este libro, siempre será PHP.
// • Usos conocidos: Estos describen sistemas reales en los que se produce el patrón
// (problema, contexto y solución). Algunas personas dicen que para que un patrón sea genuino, debe encontrarse en al menos tres contextos
// disponibles públicamente. Esto a veces se denomina la "regla de tres".
// • Patrones relacionados: Algunos patrones implican otros. Al aplicar una
// solución, puede crear el contexto en el que otra se vuelve
// útil. En esta sección se examinan estas sinergias. También se pueden analizar
// patrones que tienen similitudes con el problema o la solución, así como
// antecedentes (es decir, patrones definidos en otro lugar sobre los que se basa el
// patrón actual).