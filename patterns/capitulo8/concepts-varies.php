<?php
// El concepto que varía
// Es fácil interpretar una decisión de diseño una vez que se ha tomado, pero ¿cómo decides
// por dónde empezar?
// La Banda de los Cuatro recomienda que “encapsule el concepto que varía”.
// En términos de mi ejemplo de lección, el concepto variable es el algoritmo de costo. No sólo es
// el cálculo de costo una de las dos posibles estrategias en el ejemplo, sino que obviamente es un
// candidato para la expansión: ofertas especiales, tarifas para estudiantes extranjeros, descuentos introductorios,
// se presentan todo tipo de posibilidades.
// Rápidamente establecí que la subclasificación para esta variación era inapropiada, y
// recurrí a una declaración condicional. Al traer mi variación a la misma clase,
// subrayé su idoneidad para la encapsulación.
// La Banda de los Cuatro recomienda que busque activamente elementos variables en sus
// clases y evalúe su idoneidad para la encapsulación en un nuevo tipo. Cada alternativa en
// un condicional sospechoso puede extraerse para formar una clase que extienda un padre abstracto común. Este nuevo tipo puede luego ser utilizado por la clase o clases de las que fue
// extraido. Esto tiene los siguientes efectos:
// • Centrar la responsabilidad
// • Promover la flexibilidad a través de la composición
// • Hacer que las jerarquías de herencia sean más compactas y centradas
// • Reducir la duplicación
// Entonces, ¿cómo se detecta la variación? Una señal es el mal uso de la herencia. Esto podría
// incluir la herencia implementada de acuerdo con múltiples fuerzas a la vez (por ejemplo, conferencia/
// seminario y costo fijo/temporizado). También podría incluir la subclasificación en un algoritmo
// donde el algoritmo es incidental a la responsabilidad principal del tipo. El otro signo de variación
// adecuado para la encapsulación es, como ha visto, una expresión condicional.

// Patternitis
// Un problema para el que no existe un patrón es el uso innecesario o inapropiado
// de los patrones. Esto ha hecho que los patrones tengan mala reputación en algunos sectores. Debido a que las soluciones de patrones son
// prolijas, es tentador aplicarlas donde lo considere adecuado, ya sea que realmente
// satisfagan una necesidad o no.
// La metodología de Programación Extrema (XP) ofrece un par de principios que
// pueden aplicarse aquí. El primero es, "No lo vas a necesitar" (a menudo abreviado como YAGNI).
// Esto se aplica generalmente a las características de la aplicación, pero también tiene sentido para los patrones.
// Cuando construyo entornos grandes en PHP, tiendo a dividir mi aplicación en capas, separando
// la lógica de la aplicación de las capas de presentación y persistencia. Utilizo todo tipo de patrones centrales y empresariales en conjunto.
// Sin embargo, cuando me piden que construya un formulario de comentarios para un sitio web de una pequeña empresa,
// puedo simplemente utilizar código procedimental en un script de una sola página. No necesito enormes
// cantidades de flexibilidad; no construiré sobre la versión inicial. No necesito utilizar
// patrones que aborden problemas en sistemas más grandes. En cambio, aplico el segundo principio de XP:
// “Haz lo más simple que funcione”.
// Cuando trabajas con un catálogo de patrones, la estructura y el proceso de la solución son lo que se queda en la mente, consolidado por el ejemplo de código. Sin embargo, antes de aplicar un patrón,
// presta mucha atención al problema o a la sección “cuándo usarlo” y luego lee
// sobre las consecuencias del patrón. En algunos contextos, el remedio puede ser peor que la
// enfermedad.


// Los patrones
// Este libro no es un catálogo de patrones. Sin embargo, en los próximos capítulos, presentaré
// algunos de los patrones clave que se utilizan actualmente, proporcionando implementaciones de PHP y
// comenzándolos en el amplio contexto de la programación PHP.
// Los patrones descritos se extraerán de catálogos clave, incluidos los Patrones de diseño:
// Elementos de software orientado a objetos reutilizables (Addison-Wesley Professional, 1995),
// Patrones de arquitectura de aplicaciones empresariales de Martin Fowler (Addison-Wesley
// Professional, 2002) y Patrones básicos de J2EE: mejores prácticas y estrategias de diseño (Prentice
// Hall, 2001) de Alur et al. Utilizo la categorización de la Banda de los Cuatro como punto de partida,
// dividiendo los patrones en cinco categorías, como se indica a continuación.

// Patrones para generar objetos
// Estos patrones se ocupan de la instanciación de objetos. Esta es una categoría
// importante dado el principio, “Código para una interfaz”. Si está trabajando con clases padre
// abstractas en su diseño, entonces debe desarrollar estrategias para instanciar objetos
// de subclases concretas. Son estos objetos los que se pasarán por su sistema.
// Patrones para organizar objetos y clases
// Estos patrones lo ayudan a organizar las relaciones compositivas de sus objetos.
// De manera más simple, estos patrones muestran cómo combinar objetos y clases.
// Patrones orientados a tareas
// Estos patrones describen los mecanismos por los cuales las clases y los objetos cooperan para
// lograr objetivos.
// Patrones empresariales
// Observo algunos patrones que describen problemas típicos de programación de Internet y
// soluciones. Extraídos principalmente de Patterns of Enterprise Application Architecture y Core J2EE Patterns: Best Practices and Design Strategies, los patrones se ocupan de la presentación
// y la lógica de la aplicación.
// Patrones de bases de datos
// Esta sección proporciona un examen de patrones que ayudan con el almacenamiento y la recuperación de
// datos y con el mapeo de objetos hacia y desde bases de datos.

// Resumen
// En este capítulo, analicé algunos de los principios que sustentan muchos patrones de diseño.
// Observé el uso de la composición para permitir la combinación y recombinación de objetos
// en tiempo de ejecución, lo que dio como resultado estructuras más flexibles que las que estarían disponibles utilizando solo la herencia. También presenté el desacoplamiento, la práctica de extraer
// componentes de software de su contexto para hacerlos más aplicables en general.
// Por último, revisé la importancia de la interfaz como un medio para disociar a los clientes de
// los detalles de la implementación.
// En los próximos capítulos, examinaré algunos patrones de diseño en detalle.