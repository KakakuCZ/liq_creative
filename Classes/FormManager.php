<?php
namespace Classes;
use Classes\Objects\Order;

class FormManager
{
    /** @var  Database */
    protected $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function createNewOrder(): Order
    {
        $productTypesWithProduct = $this->database->getAllProductTypes(true);
        $order = new Order($productTypesWithProduct);
        return $order;
    }

    public function getListOfAllCustomers()
    {
        return $this->database->getAllCustomers();
    }
}