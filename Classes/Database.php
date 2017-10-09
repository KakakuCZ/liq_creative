<?php
namespace Classes;

use Classes\Objects\Customer;
use Classes\Objects\Product;
use Classes\Objects\ProductType;
use PDO;
use PDOException;

class Database
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'liq_creative';
    const DB_USER = 'root';
    const DB_PASSWORD = 'root';

    private $connection;

    private static $instance;

    private function __construct()
    {
        $dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST . '';

        try {
            $pdo = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection = $pdo;
        } catch (PDOException $e) {
            die("DB connection error: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /** ----- PRODUCT PART ----- */

    public function getAllProductTypes($withItems = false): array
    {
        $result = $this->connection->query("SELECT * FROM `product_types` ORDER BY `id` ASC")
            ->fetchAll(PDO::FETCH_CLASS, ProductType::class);

        if ($withItems === FALSE) {
            return $result;
        }

        /** @var ProductType $productTypeObject */
        foreach ($result AS $productTypeObject) {
            /** @var Product $product */
            foreach ($this->getProductsByType($productTypeObject->getId()) AS $product) {
                $productTypeObject->addItem($product);
            };
        }

        return $result;
    }

    public function getProductsByType($product_type): array
    {
        $query = $this->connection->prepare(
            "SELECT `product_list`.*, `product_types`.`name` AS 'product_type_name'
                       FROM `product_list` 
                       JOIN `product_types` ON `product_list`.`product_type_id` = `product_types`.`id` 
                          WHERE `product_type_id` = ?
                          ORDER BY `id`");
        $query->execute(array($product_type));
        $result = $query->fetchAll(PDO::FETCH_CLASS, Product::class);

        return $result;
    }

    public function getProductById($id): Product
    {
        $query = $this->connection->prepare(
            "SELECT *
                       FROM `product_list` 
                       WHERE `id` = ?");
        $query->execute(array($id));
        $result = $query->fetchObject(Product::class);

        return $result;
    }


    /** ----- CUSTOMERS PART ----- */

    public function getAllCustomers()
    {
        $customerRows =  $this->connection->query('SELECT * FROM `customers` ORDER BY `lastname` DESC')
            ->fetchAll();

        $customersArray = [];
        foreach($customerRows as $customerRow) {
            $data['id'] = $customerRow['id'];
            $data['email'] = $customerRow['email'];
            $data['firstname'] = $customerRow['firstname'];
            $data['lastname'] = $customerRow['lastname'];
            $data['phone_number'] = $customerRow['phone_number'];

            array_push($customersArray, new Customer($data));

            unset($data);
        }

        return $customersArray;
    }

    public function getCustomerByEmail($email)
    {
        $query = $this->connection->prepare(
            "SELECT *
                       FROM `customers` 
                       WHERE `email` = ?");
        $query->execute(array($email));
        $customerRow = $query->fetch();
        if ($customerRow === false) {
            return null;
        }

        $data['id'] = $customerRow['id'];
        $data['email'] = $customerRow['email'];
        $data['firstname'] = $customerRow['firstname'];
        $data['lastname'] = $customerRow['lastname'];
        $data['phone_number'] = $customerRow['phone_number'];

        return new Customer($data);

    }

    public function insertCustomer($data)
    {
        $query = $this->connection->prepare(
            "INSERT INTO `customers`
                      (`firstname`, `lastname`, `phone_number`, `email`)
                       VALUES(?,?,?,?)");
        $query->execute([$data['firstname'], $data['lastname'], $data['phone_number'], $data['email']]);
        return $this->connection->lastInsertId();
    }

}