<?php

/**
 * MODEL
 * Класс для подключения и запросов из базы данных
 * Class DAO
*/
class DAO
{
    private PDO $connection;

    /**
     * Class instance constructor
     */
    public function __construct()
    {
        $this->connection = new PDO('mysql:host=localhost;dbname=ruskon;port=3306', 'root', 'vin34232', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    /**
     * @param string $product
     * @return int
     * Getting data from a database
     */
    public function getAvgPrice(string $product): int
    {
        try {
            $sql = "SELECT AVG(price) from products WHERE name = '$product'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $price = $stmt->fetch();
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
        return round($price[0]);
    }
}
