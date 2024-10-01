<?php
// Polimorfismo
// El polimorfismo, o cambio de clases, es una característica común de los sistemas orientados a objetos.
// Ya lo ha visto varias veces en este libro.
// El polimorfismo es el mantenimiento de múltiples implementaciones detrás de una interfaz común. Esto suena complicado, pero de hecho ya debería resultarle muy familiar.
// La necesidad de polimorfismo suele indicarse por la presencia de numerosas sentencias condicionales en su código
// Cuando creé por primera vez la clase ShopProduct en el Capítulo 3, experimenté con una única clase que administraba la funcionalidad de libros y CD, además de productos genéricos.
// Para proporcionar información resumida, me basé en una declaración condicional:
class ShopProduct
{
    public function getSummaryLine(): string
    {
        $base = "{$this->title} ( {$this->producerMainName}, ";
        $base .= "{$this->producerFirstName} )";
        if ($this->type == 'book') {
            $base .= ": page count - {$this->numPages}";
        } elseif ($this->type == 'cd') {
            $base .= ": playing time - {$this->playLength}";
        }
        return $base;
    }
    function readParams(string $source): array
    {
        $params = [];
        if (preg_match("/\.xml$/i", $source)) {
            // read XML parameters from $source
        } else {
            // read text parameters from $source
        }
        return $params;
    }
    function writeParams(array $params, string $source): void
    {
        if (preg_match("/\.xml$/i", $source)) {
            // write XML parameters to $source
        } else {
            // write text parameters to $source
        }
    }
}
// Cada cláusula sugería una de las subclases que finalmente produje: XmlParamHandler
// y TextParamHandler. Estas extendieron los métodos write()
// y read() de la clase base abstracta ParamHandler:
$test = ParamHandler::getInstance($file);
$test->read(); // could be XmlParamHandler::read() or TextParamHandler::read()
$test->addParam("newkey1", "newval1");
$test->write(); // could be XmlParamHandler::write() or TextParamHandler::write()
// Es importante tener en cuenta que el polimorfismo no elimina los condicionales. Métodos como ParamHandler::getInstance() suelen determinar qué objetos devolver
// según las instrucciones switch o if. Sin embargo, estos tienden a centralizar el código condicional en un solo
// lugar. Como ha visto, PHP aplica las interfaces definidas por clases abstractas. Esto es
// útil porque podemos estar seguros de que una clase hija concreta admitirá exactamente las mismas
// firmas de método que las definidas por una clase padre abstracta. Esto incluye declaraciones de tipo
// y controles de acceso. Por lo tanto, el código cliente puede tratar a todos los hijos de una superclase común de manera intercambiable (siempre que solo dependa de la funcionalidad definida en la clase padre).