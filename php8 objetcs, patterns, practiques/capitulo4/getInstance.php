<?php
class ShopProduct
{
    private int $id = 0;

    private int|float $discount = 0;
    public function __construct(
        private string $title,
        private string $producerFirstName,
        private string $producerMainName, //no puede ser accedido por las clases hijas
        protected int|float $price /* puede ser accedido por las clases hijas */,
    ) {
    }

    public function setID(int $id): void
    {
        $this->id = $id;
    }
    public static function getInstance(int $id, \PDO $pdo): ShopProduct
    {
        $stmt = $pdo->prepare('select * from products where id=?');
        $result = $stmt->execute([$id]);

        $row = $stmt->fetch();
        
        if (empty($row)) {
            echo (var_dump($row));
            return null;
        }
        if ($row['type'] == 'book') {
            $product = new BookProduct($row['title'], $row['firstname'], $row['mainname'], (float) $row['price'], (int) $row['numpages']);
        } elseif ($row['type'] == 'cd') {
            $product = new CdProduct($row['title'], $row['firstname'], $row['mainname'], (float) $row['price'], (int) $row['playlength']);
        } else {
            $firstname = is_null($row['firstname']) ? '' : $row['firstname'];
            $product = new ShopProduct($row['title'], $firstname, $row['mainname'], (float) $row['price']);
        }
        $product->setId((int) $row['id']);
        $product->setDiscount((int) $row['discount']);
        // echo (var_dump($product));
        return $product;
    }

    public function getProducerFirstName(): string
    {
        return $this->producerFirstName;
    }
    public function getProducerMainName(): string
    {
        return $this->producerMainName;
    }
    public function setDiscount(int|float $num): void
    {
        //esto puede ser redundante, pero es una buena practica como documentacion en linea
        $this->discount = $num;
    }
    public function getDiscount(): int
    {
        return $this->discount;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getPrice(): int|float
    {
        return $this->price - $this->discount;
    }
    public function getProducer(): string
    {
        return $this->producerFirstName . ' ' . $this->producerMainName;
    }
    public function getSummaryLine(): string
    {
        $base = "{$this->title} ( {$this->producerMainName}, ";
        $base .= "{$this->producerFirstName} )";
        return $base;
    }
}

class CdProduct extends ShopProduct
{
    //estamos declarando una propiedad adicional playLength
    public function __construct(string $title, string $firstName, string $mainName, int|float $price, private int $playLength)
    {
        parent::__construct($title, $firstName, $mainName, $price);
    }
    public function getPlayLength(): int
    {
        return $this->playLength;
    }
    public function getSummaryLine(): string
    {
        $base = "{$this->title} ( {$this->producerMainName}, ";
        $base .= "{$this->producerFirstName} )";
        $base .= ": playing time - {$this->playLength}";
        return $base;
    }
}
class BookProduct extends ShopProduct
{
    public function __construct(string $title, string $firstName, string $mainName, int|float $price, private int $numPages)
    {
        //NUEVA PROPIEDAD
        parent::__construct($title, $firstName, $mainName, $price);
    }
    public function getNumberOfPages(): int
    {
        return $this->numPages;
    }
    public function getSummaryLine(): string
    {
        $base = parent::getSummaryLine();
        $base .= ": page count - $this->numPages";
        return $base;
    }
    public function getPrice(): int|float
    {
        return $this->price;
    }
}


$dsn = "sqlite:/home/jean/Documentos/PROGRAMACION/PHP/AprendiendoPHP/phpPro/php8 objetcs, patterns, practiques/capitulo4/test.sqlite3";
$pdo = new \PDO($dsn, null, null);
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$obj = ShopProduct::getInstance(1, $pdo);
echo var_dump($obj);