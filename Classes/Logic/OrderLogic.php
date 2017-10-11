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

class OrderLogic
{
    private static $instance;

    private $database;

    private function __construct()
    {
        $this->database = Database::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new OrderLogic();
        }

        return self::$instance;
    }

    public function saveOrder(Order $order)
    {
        $orderData['customer_id'] = $order->getCustomer()->getId();
        $orderData['width'] = $order->getWidth();
        $orderData['length'] = $order->getLength();

        if ($order->getShipping() === TRUE) {
            $orderData['shipping_boolean'] = 1;
        } else {
            $orderData['shipping_boolean'] = 0;
        }

        $orderData['total_price'] = $order->getTotalPrice();
        $orderId = $this->database->insertOrder($orderData);

        $productTypes = ['baseMedia', 'printMedia', 'finishing'];

        if ($order->getBaseMedia() !== null) {
            $this->database->insertProductToOrder($orderId, $order->getBaseMedia()->getId());
        }

        if ($order->getPrintMedia() !== null) {
            $this->database->insertProductToOrder($orderId, $order->getPrintMedia()->getId());
        }

        /** @var Product $finishing */
        foreach ($order->getFinishing() as $finishing) {
            $this->database->insertProductToOrder($orderId, $finishing->getId());
        }
    }
}
