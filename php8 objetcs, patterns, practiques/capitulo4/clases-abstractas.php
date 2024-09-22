<?php
abstract class ShopProductWriter
{
    protected array $products = [];
    public function addProduct(ShopProduct $shopProduct): void
    {
        $this->products[] = $shopProduct;
    }

    abstract public function write(): void; //Un método abstracto no puede tener una implementación. Se declara de la forma habitual, pero finaliza la declaración con un punto y coma en lugar de un cuerpo de método
}
// Puedes crear métodos y propiedades de forma normal, pero cualquier intento de crear una instancia de un objeto abstracto de esta manera provocará un error:
// $writer = new ShopProductWriter(); //Error: Cannot instantiate abstract class

// class ErroredWriter extends ShopProductWriter
// {
    // esto genera error \ErroredWriter contains 1 abstract method and must therefore be declared abstract or implement the remaining methods
// Por lo tanto, cualquier clase que extienda una clase abstracta debe implementar todos los métodos abstractos o declararse abstracta
// }

class XmlProductWriter extends ShopProductWriter
{
    public function write(): void
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('products');
        foreach ($this->products as $shopProduct) {
            $writer->startElement('product');
            $writer->writeAttribute('title', $shopProduct->getTitle());
            $writer->startElement('summary');
            $writer->text($shopProduct->getSummaryLine());
            $writer->endElement(); // summary
            $writer->endElement(); // product
        }
        $writer->endElement(); // products
        $writer->endDocument();
        print $writer->flush();
    }
}

class TextProductWriter extends ShopProductWriter
{
    public function write(): void
    {
        $str = "PRODUCTS:\n";
        foreach ($this->products as $shopProduct) {
            $str .= $shopProduct->getSummaryLine() . "\n";
        }
        print $str;
    }
}
