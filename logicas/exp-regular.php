<?php
// ¿Qué es una Expresión Regular?

// Una expresión regular (regex) es una secuencia de caracteres que forma un patrón de búsqueda. Este patrón se utiliza para buscar y manipular texto de manera flexible y poderosa.
// Elementos Básicos de Expresiones Regulares

    // Delimitadores: Las expresiones regulares suelen estar rodeadas por caracteres de delimitación, como / en PHP.
        // Ejemplo: /patrón/

    // Clases de Caracteres: Se definen con corchetes [] para coincidir con cualquiera de los caracteres dentro.
        // Ejemplo: [abc] coincide con "a", "b" o "c".

    // Escapando Caracteres Especiales: Para buscar caracteres que tienen un significado especial (como . o *), los escapamos con una barra invertida \.
        // Ejemplo: \. coincide con un punto literal.

// Operadores de Repetición

    // * (Cero o más): Coincide con cero o más repeticiones del carácter o grupo anterior.
        // Ejemplo: a* coincide con "", "a", "aa", "aaa", etc.

    // + (Uno o más): Coincide con una o más repeticiones del carácter o grupo anterior.
        // Ejemplo: a+ coincide con "a", "aa", "aaa", pero no con "".

    // ? (Cero o uno): Coincide con cero o una repetición del carácter o grupo anterior.
        // Ejemplo: a? coincide con "" o "a".

    // {n} (Exactamente n): Coincide exactamente con n repeticiones del carácter o grupo anterior.
        // Ejemplo: a{3} coincide solo con "aaa".

    // {n,} (Al menos n): Coincide con al menos n repeticiones del carácter o grupo anterior.
        // Ejemplo: a{2,} coincide con "aa", "aaa", "aaaa", etc.

    // {n,m} (Entre n y m): Coincide con entre n y m repeticiones del carácter o grupo anterior.
        // Ejemplo: a{2,4} coincide con "aa", "aaa", o "aaaa", pero no con "a" o "aaaaa".

// Ejemplos de Expresiones Regulares

    // Buscar Secuencias de Espacios
    //     Expresión: '/\s+/'
    //     Descripción: Coincide con una o más espacios en blanco (espacios, tabulaciones, nuevas líneas).

    // Buscar Dígitos
    //     Expresión: '/\d+/'
    //     Descripción: Coincide con una o más cifras. Por ejemplo, en "123 abc 45", coincide con "123" y "45".

    // Buscar Palabras de Longitud Específica
    //     Expresión: '/\b\w{4}\b/'
    //     Descripción: Coincide con palabras de exactamente 4 letras. En "Esto es una prueba", coincide con "esto".

    // Buscar Combinaciones de Caracteres
        // Expresión: '/a{2,4}/'
        // Descripción: Coincide con "aa", "aaa", o "aaaa". No coincide con "a" o "aaaaa".

// Ejemplo en Código PHP

// Supongamos que queremos encontrar todas las palabras en una cadena que tienen entre 2 y 4 letras.

// php

$input = "A casa es bonita y el coche es azul.";
$pattern = '/\b\w{2,4}\b/'; // Palabras de longitud entre 2 y 4

preg_match_all($pattern, $input, $matches);
print_r($matches[0]); // Imprime las palabras que coinciden

// Resumen

    // Clases de Caracteres: Usamos [] para definir un conjunto de caracteres.
    // Repeticiones: Usamos *, +, ?, {n}, {n,}, y {n,m} para definir cuántas veces queremos que aparezca un carácter.
    // Coincidencias: Las expresiones regulares nos permiten buscar patrones específicos en texto.

// Si necesitas más ejemplos o aclaraciones sobre algún concepto en particular, ¡hazmelo saber!
$input = "Esto es una prueba de coche caro.";
$pattern = '/\b\w{4}\b/'; // Coincidir con palabras de exactamente 4 letras

preg_match_all($pattern, $input, $matches);
print_r($matches); // Muestra las palabras que coinciden


// Coincidencias de Límites de Palabra:

//     En la cadena "hola mundo":
//         La posición antes de "hola" y después de "mundo" coincide con \b.
//         También hay un límite antes y después de una palabra que comienza o termina con un espacio o un signo de puntuación.

// Uso en una Expresión Regular:

//     En el patrón '/\bcat\b/':
//         Coincide con la palabra "cat" solo si está rodeada por límites de palabra, es decir, "cat" no debe ser parte de otra palabra como "catalog" o "scattered".
//     Ejemplo:

//     php
    echo "Ejemplo de Límites de Palabra\n";
        $input = "The cat sat on the mat.";
        preg_match_all('/\bcat\b/', $input, $matches);
        print_r($matches[0]); // Imprime "cat"

//     Ejemplo sin Límite de Palabra:
//         Usando el patrón '/cat/', coincidiría en:
//             "catalog"
//             "scattercat"
//         Pero '/\bcat\b/' solo coincidiría con "cat".

// Resumen

//     /: Delimitadores de la expresión regular.
//     \b: Indica un límite de palabra, asegurando que solo coincidamos con palabras completas.
//     /: Finaliza la expresión.

// Esto hace que \b sea muy útil para buscar palabras exactas en textos, evitando coincidencias parciales dentro de otras palabras. Si necesitas más ejemplos o aclaraciones, ¡dímelo!

// DIFERENCIAS CON () Y COINCIDENCIAS Y RESULTADOS DEL PREG_MATCH

// $input = "El precio del producto es 150 dólares.";


// Y queremos extraer tanto el número como la palabra "dólares".

### Expresión Regular

// Utilizaremos la siguiente expresión regular:
// '/(\d+) (\w+)/'


// - **`(\d+)`**: Captura uno o más dígitos (el precio).
// - **`(\w+)`**: Captura una o más letras (la unidad, en este caso "dólares").

### Código Completo

// Aquí está el código que utiliza `preg_match()`:

$input = "El precio del producto es 150 dólares.";
if (preg_match('/(\d+) (\w+)/', $input, $matches)) {
    echo "Coincidencia completa: " . $matches[0] . "\n"; // Coincidencia completa
    echo "Precio: " . $matches[1] . "\n";              // Primer grupo capturado (dígitos)
    echo "Unidad: " . $matches[2] . "\n";              // Segundo grupo capturado (palabra)
}


### Salida Esperada

// Si ejecutas el código, la salida será:

// Coincidencia completa: 150 dólares
// Precio: 150
// Unidad: dólares

### Desglose de los Resultados

// 1. **`$matches[0]`**:
//    - Contiene la coincidencia completa de la expresión regular.
//    - En este caso, será `"150 dólares"`.

// 2. **`$matches[1]`**:
//    - Contiene la primera coincidencia del primer grupo de captura (lo que está entre los primeros paréntesis).
//    - Aquí, será `"150"` (los dígitos).

// 3. **`$matches[2]`**:
//    - Contiene la primera coincidencia del segundo grupo de captura (lo que está entre los segundos paréntesis).
//    - Aquí, será `"dólares"` (la palabra).

### Resumen Visual

// - **Coincidencia Completa**:
//   - `$matches[0]`: `"150 dólares"`
  
// - **Primer Grupo Capturado**:
//   - `$matches[1]`: `"150"`

// - **Segundo Grupo Capturado**:
//   - `$matches[2]`: `"dólares"`

### Importancia de Usar Paréntesis

// - **Sin Paréntesis**: Si escribes la expresión como `'/\d+ \w+/'`, solo obtendrás la coincidencia completa en `$matches[0]`, y no podrás acceder a los componentes individuales.
// - **Con Paréntesis**: Al usar `'/(\d+) (\w+)/'`, puedes capturar y acceder a partes específicas de la coincidencia.


// 1. Con ^ y $

// if (preg_match('/^(.*)(L|R)(.*)$/', $line, $matches)) {

    // ^: Indica que la coincidencia debe comenzar desde el inicio de la cadena.
    // $: Indica que la coincidencia debe terminar en el final de la cadena.
    // Significado: Esta expresión garantiza que toda la cadena se utiliza en la coincidencia. Capturará cualquier texto antes y después de L o R, pero también asegura que no haya caracteres adicionales antes del inicio o después del final de la cadena.

// 2. Sin ^ y $

// if (preg_match('/(.*)(L|R)(.*)/', $line, $matches)) {
// }
    // Sin ^ y $: Esto significa que la coincidencia puede ocurrir en cualquier parte de la cadena.
    // Significado: Esta expresión capturará L o R y cualquier texto antes y después de ellos, pero no requiere que la cadena coincida completamente. Puede haber otros caracteres antes o después de la coincidencia.
