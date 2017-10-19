<?php

namespace Classes\Logic;

use Classes\Database;
use Classes\Exceptions\Customers\BadFormatOfCustomersEmailException;
use Classes\Exceptions\Customers\CustomerAlreadyExistsException;
use Classes\Exceptions\Customers\CustomersEmailIsTooLongException;
use Classes\Exceptions\Customers\IncorrectFormatOfCustomersPhone;
use Classes\Exceptions\Customers\IncorrectLengthOfCustomersFirstname;
use Classes\Exceptions\Customers\IncorrectLengthOfCustomersLastname;
use Classes\Helpers\EmailHelper;
use Classes\Helpers\StringHelper;
use Classes\Objects\Customer;
use Classes\Objects\Order;
use Classes\Objects\Product;
use Helpers\ConstantHelper;
use Helpers\PhoneHelper;

class OrderLogic {

    private static $instance;
    private $database;

    private function __construct() {
        $this->database = Database::getInstance();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new OrderLogic();
        }

        return self::$instance;
    }

    public function saveOrder(Order $order) {
        $orderData['customer_id'] = $order->getCustomer()->getId();
        $orderData['order_name'] = $order->getOrderName();
        $orderData['width'] = $order->getWidth() * 1000;
        $orderData['length'] = $order->getLength() * 1000;

        if ($order->getShipping() === TRUE) {
            $orderData['shipping_boolean'] = 1;
        } else {
            $orderData['shipping_boolean'] = 0;
        }

        $orderData['total_price'] = $order->getTotalPrice();
        $orderId = $this->database->insertOrder($orderData);

        if ($order->getBaseMedia() !== null) {
            $this->database->insertProductToOrder($orderId, $order->getBaseMedia()->getId());
        }

        if ($order->getPrintMedia() !== null) {
            $this->database->insertProductToOrder($orderId, $order->getPrintMedia()->getId());
        }

        /** @var Product $finishing */
        foreach ($order->getFinishing() as $finishing) {
            if ($finishing === null) {
                continue;
            }
            $this->database->insertProductToOrder($orderId, $finishing->getId());
        }
    }

    public function updateOrder(Order $order, $orderID) {
        $orderData['customer_id'] = $order->getCustomer()->getId();
        $orderData['order_name'] = $order->getOrderName();
        $orderData['width'] = $order->getWidth() * 1000;
        $orderData['length'] = $order->getLength() * 1000;
        if ($order->getShipping() === TRUE) {
            $orderData['shipping_boolean'] = 1;
        } else {
            $orderData['shipping_boolean'] = 0;
        }
        $orderData['total_price'] = $order->getTotalPrice();
        $this->database->updateOrderByID($orderData, $orderID);
        if ($order->getBaseMedia() !== null) {
            $this->database->updateProductsByID($orderID, $order->getBaseMedia()->getId(), "base media");
        } else {
            $this->database->deleteOrder($orderID, "base media");
        }
        if ($order->getPrintMedia() !== null) {
            $this->database->updateProductsByID($orderID, $order->getPrintMedia()->getId(), "print media");
        } else {
            $this->database->deleteOrder($orderID, "print media");
        }
        /** @var Product $finishing */
        foreach ($order->getFinishing() as $finishing) {
            if ($finishing !== null) {
                $finishingIDs[] = $finishing->getId();
            }
        }
        $this->database->updateProductsByID($orderID, $finishingIDs, "finishing");
    }

}
