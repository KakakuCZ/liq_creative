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

    public function createNewEmptyOrder(): Order
    {
        $productTypesWithProduct = $this->database->getAllProductTypes(true);
        $order = new Order($productTypesWithProduct);
        return $order;
    }

    public function createOrderBySelectedOptions(array $inputs): Order
    {
        $order = $this->createNewEmptyOrder();
        $inputs = $this->makeObjectsArrayFromInputsArray($inputs);
        $order->setItems($inputs);
        return $order;
    }

    public function getListOfAllCustomers()
    {
        return $this->database->getAllCustomers();
    }

    private function makeObjectsArrayFromInputsArray($inputs)
    {
        if ($inputs['baseMedia'] != 0) {
            $inputs['baseMedia'] = $this->database->getProductById($inputs['baseMedia']);
        } else {
            $inputs['baseMedia'] = null;
        }

        if ($inputs['printMedia'] != 0) {
            $inputs['printMedia'] = $this->database->getProductById($inputs['printMedia']);
        } else {
            $inputs['printMedia'] = null;
        }

        foreach($inputs['finishing'] as $key => $finishingOne) {
            if ($finishingOne != 0) {
                $inputs['finishing'][$key] = $this->database->getProductById($inputs['finishing']);
            } else {
                $inputs['finishing'][$key] = null;
            }
        }

        if ($inputs['shipping'] == 1) {
            $inputs['shipping'] = true;
        } else {
            $inputs['shipping'] = false;
        }

        return $inputs;
    }
}