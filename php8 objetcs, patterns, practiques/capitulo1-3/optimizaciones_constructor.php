<?php

class ShopProductV1
{
    public $title = 'default product';
    public $producerMainName = 'main name';
    public $producerFirstName = 'first name';
    public $price = 0;
    public function getProducer()
    {
        return $this->producerFirstName . ' ' . $this->producerMainName;
    }
}
//PHP 7.0 O BEDFORE
class ShopProduct
{
    public $title;
    public $producerMainName;
    public $producerFirstName;
    public $price = 0;

    //PHP 8 O SUPERIOR
    public function __construct($title, $firstName, $mainName, $price)
    {
        $this->title = $title;
        $this->producerFirstName = $firstName;
        $this->producerMainName = $mainName;
        $this->price = $price;
    }
    public function getProducer()
    {
        return $this->producerFirstName . ' ' . $this->producerMainName;
    }
}

//TODO: CONSTRUCTOR Al incluir una palabra clave de visibilidad para los argumentos del constructor, puede combinarlos con declaraciones de propiedades y
// asignarles propiedades al mismo tiempo. Aquí hay una nueva versión de ShopProduct:
$product1 = new ShopProduct('Shop Catalogue', '', '', 0);
//BEFORE
class ShopProductV2
{
    public function __construct(public $title, public $producerFirstName = '', public $producerMainName = '', public $price = 0)
    {
        $this->title = $title;
        $this->producerFirstName = $producerFirstName;
        $this->producerMainName = $producerMainName;
        $this->price = $price;
    }
}
//AFTER PHP 7
$product1 = new ShopProductV2('Shop Catalogue');
//AFTER PHP 8
$product1 = new ShopProductV2(price: 0.7, title: 'Shop Catalogue');

?>
