<?php
require_once './Parser.php';

$parser = new Parser();

$products = $parser->getProductsLinks('https://saratov.metal100.ru/prodazha/Truboprovodnaya-armatura/Flanets_stalnoy');

$info = [];

foreach ($products as $product) {
    foreach ($parser->getInfo($product['url']) as $item) {
        $info[] = $item;
    }
}

$connection = new PDO('mysql:host=localhost;dbname=ruskon;port=3306', 'root', 'vin34232', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

try {
    $sql = "INSERT INTO products(id, name, company, price) VALUES (null, :name, :company, :price)";
    $stmt = $connection->prepare($sql);
    foreach ($info as $item) {
        $stmt->execute(['name' => $item['name'], 'company' => $item['company'], 'price' => $item['price']]);
    }
} catch (PDOException $ex) {
    var_dump($ex->getMessage());
}
