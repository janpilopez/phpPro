EL METODO ES DEFINIDO DENTRO DE UNA CLASE, PUEDEN SER GETTERS O SETTERS, POR EJEMPLO,
SE DEBE DEFINIR EL TIPO DE DECLARACION SI ES PUBLICA, PRIVADA, PROTECTED.
class ShopProduct
{
 public $title = "default product";
 public $producerMainName = "main name";
 public $producerFirstName = "first name";
 public $price = 0;
 public function getProducer()
 {
 return $this->producerFirstName . " "
 . $this->producerMainName;
 }
}


LA FUNCIONES ES NETAMENTE LA FUNCION FUERA DE UNA CLASE, PUEDE SER LLAMADA DESDE CUALQUIER PARTE