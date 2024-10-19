<?php
// Patrón Abstract Factory
// En aplicaciones grandes, puede necesitar fábricas que produzcan conjuntos relacionados de clases. 
// El patrón Abstract Factory aborda este problema.
// El problema
// Veamos nuevamente el ejemplo del organizador. Administro la codificación en dos formatos, BloggsCal
// y MegaCal. Puedo hacer crecer esta estructura horizontalmente agregando más formatos de codificación,
// pero ¿cómo puedo hacerlo verticalmente, agregando codificadores para diferentes tipos de objetos PIM? De hecho,
// ya he estado trabajando en este patrón.
// En la Figura 9-6, puede ver las familias paralelas con las que quiero trabajar. Estas son citas (Appt), 
// cosas por hacer (Ttd) y contactos (Contact).

// Las clases BloggsCal no están relacionadas entre sí por herencia (aunque
// podrían implementar una interfaz común), pero son funcionalmente paralelas. Si el
// sistema está trabajando actualmente con BloggsTtdEncoder, también debería estar trabajando con
// BloggsContactEncoder.
// Para ver cómo hago cumplir esto, puede comenzar con la interfaz, como hice con el patrón Factory
// Method (consulte la Figura 9-7).
// Implementación
// La clase abstracta CommsManager define la interfaz para generar cada uno de los tres
// productos (ApptEncoder, TtdEncoder y ContactEncoder). Es necesario implementar
// un creador concreto para generar los productos concretos para una familia
// en particular. Lo ilustro para el formato BloggsCal en la Figura 9-8.
abstract class CommsManager
{
    abstract public function getHeaderText(): string;
    abstract public function getApptEncoder(): ApptEncoder;
    abstract public function getTtdEncoder(): TtdEncoder;
    abstract public function getContactEncoder(): ContactEncoder;
    abstract public function getFooterText(): string;
}
class BloggsCommsManager extends CommsManager
{
    public function getHeaderText(): string
    {
        return "BloggsCal header\n";
    }
    public function getApptEncoder(): ApptEncoder
    {
        return new BloggsApptEncoder();
    }
    public function getTtdEncoder(): TtdEncoder
    {
        return new BloggsTtdEncoder();
    }
    public function getContactEncoder(): ContactEncoder
    {
        return new BloggsContactEncoder();
    }
    public function getFooterText(): string
    {
        return "BloggsCal footer\n";
    }
}
// Tenga en cuenta que utilizo el patrón Factory Method en este ejemplo. getContactEncoder()
// es abstracto en CommsManager y se implementa en BloggsCommsManager. Los patrones de diseño
// tienden a funcionar juntos de esta manera, un patrón crea el contexto que se presta a otro. 
// En la Figura 9-9, agrego soporte para el formato MegaCal.
// Consecuencias
// Veamos qué aporta este patrón:
// • En primer lugar, desacoplo mi sistema de los detalles de implementación.
// Puedo añadir o eliminar cualquier cantidad de formatos de codificación en mi ejemplo
// sin provocar un efecto dominó.
// • Hago cumplir la agrupación de elementos funcionalmente relacionados de mi sistema.
// Por tanto, al utilizar BloggsCommsManager, tengo la garantía de que trabajaré
// solamente con clases relacionadas con BloggsCal.
// • Añadir nuevos productos puede ser un fastidio. No solo tengo que crear
// implementaciones concretas del nuevo producto, sino que también tengo que
// modificar el creador abstracto y cada uno de sus
// implementadores concretos para que sea compatible.
// Muchas implementaciones del patrón Abstract Factory utilizan el patrón Factory Method. Esto puede deberse a que la
//  mayoría de los ejemplos están escritos en Java o C++. Sin embargo, PHP
// no tiene que imponer un tipo de retorno para un método (aunque ahora puede), lo que nos proporciona cierta flexibilidad
//  que podemos aprovechar.
// En lugar de crear métodos separados para cada método de fábrica, puede crear un único método make() que utilice un 
// argumento de marca para determinar qué objeto devolver:
interface Encoder
{
    public function encode(): string;
}
// listing 09.30
abstract class CommsManager
{
    public const APPT = 1;
    public const TTD = 2;
    public const CONTACT = 3;
    abstract public function getHeaderText(): string;
    abstract public function make(int $flag_int): Encoder;
    abstract public function getFooterText(): string;
}
// listing 09.31
class BloggsCommsManager extends CommsManager
{
    public function getHeaderText(): string
    {
        return "BloggsCal header\n";
    }
    public function make(int $flag_int): Encoder
    {
        switch ($flag_int) {
            case self::APPT:
                return new BloggsApptEncoder();
            case self::CONTACT:
                return new BloggsContactEncoder();
            case self::TTD:
                return new BloggsTtdEncoder();
        }
    }
    public function getFooterText(): string
    {
        return "BloggsCal footer\n";
    }
}
// Como puede ver, he hecho que la interfaz de clase sea más compacta. Sin embargo, lo he hecho a un
// costo considerable. Al usar un método de fábrica, defino una interfaz clara y
// obligo a todos los objetos de fábrica concretos a respetarla. Al usar un único método make(), debo
// recordar admitir todos los objetos de producto en todos los creadores concretos. También introduzco
// condicionales paralelos, ya que cada creador concreto debe implementar las mismas pruebas de indicadores. Una
// clase cliente no puede estar segura de que los creadores concretos generen todos los productos porque
// los aspectos internos de make() son una cuestión de elección en cada caso.
// Por otro lado, puedo crear creadores más flexibles. La clase creadora base puede
// proporcionar un método make() que garantice una implementación predeterminada de cada
// familia de productos. Los hijos concretos podrían modificar este comportamiento de forma selectiva. 
// Dependería de las clases creadoras que implementen llamar al método make() predeterminado después de proporcionar su propia
// implementación.
// Verá otra variación del patrón Abstract Factory en la siguiente sección.