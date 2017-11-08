<?php

namespace Classes;

use Classes\Objects\Customer;
use Classes\Objects\Product;
use Classes\Objects\ProductType;
use PDO;
use PDOException;

class Database {

    const DB_HOST = 'localhost';
    const DB_NAME = 'liq_creative';
    const DB_USER = 'root';
    const DB_PASSWORD = '';

    private $connection;
    private static $instance;

    private function __construct() {
        $dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST . '';

        try {
            $pdo = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection = $pdo;
        } catch (PDOException $e) {
            die("DB connection error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /** ----- PRODUCT PART ----- */
    public function getAllProductTypes($withItems = false): array {
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
            }
        }

        return $result;
    }

    public function getProductsByType($product_type): array {
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

    public function getProductById($id): Product {
        $query = $this->connection->prepare(
                "SELECT *
                       FROM `product_list` 
                       WHERE `id` = ?");
        $query->execute(array($id));
        $result = $query->fetchObject(Product::class);

        return $result;
    }

    /** ----- CUSTOMERS PART ----- */
    public function getAllCustomers() {
        $customerRows = $this->connection->query('SELECT * FROM `customers` ORDER BY `lastname` ASC')
                ->fetchAll();

        $customersArray = [];
        foreach ($customerRows as $customerRow) {
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

    public function getCustomerDetailsByID($id) {
        $query = $this->connection->prepare(
                "SELECT phone_number, email "
                . "FROM customers "
                . "WHERE id = ?");
        $query->execute([$id]);
        return $query->fetchAll();
    }

    public function getCustomerByEmail($email) {
        $query = $this->connection->prepare(
                "SELECT *
                       FROM `customers` 
                       WHERE `email` = ?");
        $query->execute(array($email));
        $customerRow = $query->fetch();
        if ($customerRow === false) {
            return null;
        }

        $data = $this->makeCustomerRowForArray($customerRow);

        return new Customer($data);
    }

    public function getCustomerById($id) {
        $query = $this->connection->prepare(
                "SELECT *
                       FROM `customers` 
                       WHERE `id` = ?");
        $query->execute(array($id));
        $customerRow = $query->fetch();
        if ($customerRow === false) {
            return null;
        }

        $data = $this->makeCustomerRowForArray($customerRow);

        return new Customer($data);
    }

    public function insertCustomer($data) {
        $query = $this->connection->prepare(
                "INSERT INTO `customers`
                      (`firstname`, `lastname`, `phone_number`, `email`)
                       VALUES(?,?,?,?)");
        $query->execute([$data['firstname'], $data['lastname'], $data['phone_number'], $data['email']]);
        return $this->connection->lastInsertId();
    }

    private function makeCustomerRowForArray($customerRow) {
        $data['id'] = $customerRow['id'];
        $data['email'] = $customerRow['email'];
        $data['firstname'] = $customerRow['firstname'];
        $data['lastname'] = $customerRow['lastname'];
        $data['phone_number'] = $customerRow['phone_number'];

        return $data;
    }

    /** Order */
    public function insertOrder($data) {
        $query = $this->connection->prepare(
                "INSERT INTO `orders`
                      (`customer_id`, `name`, `date`, `size_1`, `size_2`, `ink`, `shipping`, `total_price`)
                      VALUES(?, ?, now(), ?, ?, ?, ?, ?)");
        $query->execute([$data['customer_id'], $data['order_name'], $data['width'], $data['length'], $data['ink_boolean'], $data['shipping_boolean'], $data['total_price']]);

        return $this->connection->lastInsertId();
    }

    public function insertProductToOrder($orderId, $productId) {
        $query = $this->connection->prepare(
                "INSERT INTO `orders_part`
                      (`order_id`, `product_id`)
                      VALUES(?,?)");
        $query->execute([$orderId, $productId]);

        return $this->connection->lastInsertId();
    }

    public function getOrdersByCustomer($customerID) {
        $query = $this->connection->prepare(
                "SELECT * "
                . "FROM orders "
                . "WHERE customer_id = ?");
        $query->execute([$customerID]);
        return $query->fetchAll();
    }

    public function getSpecOrder($orderID) {
        $query = $this->connection->prepare("SELECT O.id, O.name, O.size_1, O.size_2, O.ink, O.shipping, O.total_price, OP.product_id, PT.id AS 'type'"
                . "FROM orders O, orders_part OP, product_list PL, product_types PT "
                . "WHERE O.id = ? AND OP.order_id = ? AND OP.product_id = PL.id AND PT.id = PL.product_type_id");
        $query->execute([$orderID, $orderID]);
        return $query->fetchAll();
    }

    public function updateOrderByID($data, $orderID) {
        $query = $this->connection->prepare(
                "UPDATE orders "
                . "SET name = ?, size_1 = ?, size_2 = ?, ink = ?, shipping = ?, total_price = ? "
                . "WHERE id = ?");
        $query->execute([$data["order_name"], $data["width"], $data["length"], $data["ink_boolean"], $data["shipping_boolean"], $data["total_price"], $orderID]);
    }

    public function updateProductsByID($orderID, $productID, $type) {
        $oldOrderProducts = $this->selectOldProduct($type, $orderID);
        if ($type !== "finishing") {
            if (empty($oldOrderProducts)) {
                $this->insertProductToOrder($orderID, $productID);
            } else {
                $this->updatePartsByIDs($productID, $oldOrderProducts[0]["id"], $orderID);
            }
        } else {
            $sizeArray = array(sizeof($oldOrderProducts), sizeof($productID));
            switch ($sizeArray) {
                case $sizeArray[0] === 1 && $sizeArray[1] === 1:
                    $this->updatePartsByIDs($productID[0], $oldOrderProducts[0]["id"], $orderID);
                    break;
                case $sizeArray[0] === 2 && $sizeArray[1] === 2:
                    $this->updatePartsByIDs($productID[0], $oldOrderProducts[0]["id"], $orderID);
                    $this->updatePartsByIDs($productID[1], $oldOrderProducts[1]["id"], $orderID);
                    break;
                case $sizeArray[0] === 1 && $sizeArray[1] === 2:
                    $this->updatePartsByIDs($productID[0], $oldOrderProducts[0]["id"], $orderID);
                    $this->insertProductToOrder($orderID, $productID[1]);
                    break;
                case $sizeArray[0] === 2 && $sizeArray[1] === 1:
                    $this->updatePartsByIDs($productID[0], $oldOrderProducts[0]["id"], $orderID);
                    $this->deletePartsByID($oldOrderProducts[1]["id"]);
                    break;
                default:
                    break;
            }
        }
    }

    private function deletePartsByID($oldProductID) {
        $query = $this->connection->prepare(
                "DELETE "
                . "FROM orders_part "
                . "WHERE id = ?");
        $query->execute([$oldProductID]);
    }

    private function updatePartsByIDs($productID, $oldProductID, $orderID) {
        $query = $this->connection->prepare(
                "UPDATE orders_part "
                . "SET product_id = ? "
                . "WHERE id = ? AND order_id = ?");
        $query->execute([$productID, $oldProductID, $orderID]);
    }

    public function selectOldProduct($type, $orderID) {
        $query = $this->connection->prepare(
                "SELECT OP.id, OP.product_id "
                . "FROM orders_part OP, product_list PL, product_types PT "
                . "WHERE OP.product_id = PL.id AND PL.product_type_id = PT.id ANd PT.name = ? AND OP.order_id = ?");
        $query->execute([$type, $orderID]);
        return $query->fetchAll();
    }

    public function deleteOrder($orderID, $type) {
        $oldOrderProducts = $this->selectOldProduct($type, $orderID);
        if ($type !== "finishing") {
            $query = $this->connection->prepare(
                    "DELETE "
                    . "FROM orders_part "
                    . "WHERE id = ?");
            $query->execute([$oldOrderProducts[0]["id"]]);
        }
    }

}
