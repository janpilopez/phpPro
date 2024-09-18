<?php
// Clases anónimas
// PHP 7 introdujo las clases anónimas. Son útiles cuando se necesita crear y
// derivar una instancia de una clase pequeña, cuando la clase padre en cuestión es simple y
// específica del contexto local.
// Volvamos a nuestro ejemplo de PersonWriter. Comenzaré creando una interfaz esta vez:

interface PersonWriter
{
    public function write(Person $person): void;
}
// Now, here’s a version of the Person class that can use a PersonWriter object:
class Person
{
    public function output(PersonWriter $writer): void//instanciamos personwrite y le pasamos la propia clase person a su metodo write
    {
        $writer->write($this);
    }
    public function getName(): string
    {
        return 'Bob';
    }
    public function getAge(): int
    {
        return 44;
    }
}

//CONTINUA EL EJEMPLO ABAJO NO TERMINA AUN
// El método output() acepta una instancia de PersonWriter y luego pasa una instancia
// de la clase actual a su método write(). De esta manera, la clase Person está bien
// aislada de la implementación del escritor.
// Pasando al código del cliente, si necesitamos un escritor para imprimir valores de nombre y edad para un
// objeto Person, podemos seguir adelante y crear una clase de la manera habitual. Pero es una implementación tan
// trivial que igualmente podríamos crear una clase y pasársela a Person al mismo
// tiempo:


// Las clases anónimas no admiten cierres. En otras palabras, no se puede acceder a las variables declaradas en
// un ámbito más amplio dentro de la clase. Sin embargo, puedes pasar valores al constructor de una
// clase anónima. Vamos a crear un PersonWriter un poco más complejo

$person = new Person();
//CON CONSTRUCTOR PORQUE PASAMOS UN ARGUMENTO
$person->output(
    //el argunmento de ruta se pasa al constructor
    //cabe mencionar que se ejecuta todo el bloque de código
    //la ruta es el argumento que se pasa al constructor
    new class ('/tmp/persondump') implements PersonWriter {
        private $path;
        public function __construct(string $path)
        {
            $this->path = $path;
        }
        public function write(Person $person): void
        {
            //guardamos en archivo local
            file_put_contents($this->path, $person->getName() . ' ' . $person->getAge() . "\n");
            echo "OKEY";
        }
    },
);//ESTA ULTIMA FUNCION ANONIMA  PUEDE SERVIR SI NECESITAMOS MAS DATOS COMO GUARDAR EN ALGUN LADOS O MAS LOGICA PORQUE NOS PERMITE RECIBIR PARAMETROS

// Pasé un argumento de ruta al constructor. Este valor se almacenó en la propiedad $path
// y, finalmente, lo utilizó el método write().
// Por supuesto, si su clase anónima comienza a crecer en tamaño y complejidad, resulta más sensato crear una clase con
//  nombre en un archivo de clase. Esto es especialmente cierto si descubre que duplica su clase anónima en más de un lugar.

//NOTA: EN RESUMEN SIRVE PARA EXTENDER FUNCIONALIDAD CUANDO SON CLASES PEQUEÑAS Y NO NECESITAN SER REUTILIZADAS O MAYOR CONFIGURACION
//SU USO ES SENCILLO, SI NECESITAMOS ALGO MAS GRANDE YA NO DEBEMOS USAR CLASES ANONIMAS