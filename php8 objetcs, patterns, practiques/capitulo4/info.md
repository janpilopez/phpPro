Métodos y propiedades estáticos
Todos los ejemplos del capítulo anterior funcionaban con objetos. Caractericé a las
clases como plantillas a partir de las cuales se producen los objetos y a los objetos como instancias activas
de clases: las cosas cuyos métodos invocas y cuyas propiedades accedes.
Di a entender que, en la programación orientada a objetos, el trabajo real lo realizan las instancias de
clases. Después de todo, las clases son simplemente plantillas para los objetos.
De hecho, no es tan sencillo. Puedes acceder tanto a los métodos como a las propiedades en el
contexto de una clase en lugar de en el de un objeto. Dichos métodos y propiedades son “estáticos”
y deben declararse como tales utilizando la palabra clave static

class StaticExample
{
    public static int $aNum = 0;
    
    public static function sayHello(): void
    {
        print "hello";
    }
}

para acceder a una clase estatica  o sus propiedades

print StaticExample::$aNum;

StaticExample::sayHello();

El código de clase puede usar la palabra clave parent para acceder a una superclase sin utilizar su nombre de clase
class StaticExample2
{
    public static int $aNum = 0;
    public static function sayHello(): void
    {
        <!-- Desde dentro de una clase, puedo usar la palabra clave self: -->
        self::$aNum++;
        print "hello (" . self::$aNum . ")\n";
    }
}