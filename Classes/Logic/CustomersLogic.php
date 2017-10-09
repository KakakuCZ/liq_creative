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
use Helpers\ConstantHelper;
use Helpers\PhoneHelper;

class CustomersLogic
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
            self::$instance = new CustomersLogic;
        }

        return self::$instance;
    }

    public function validateEmail($email)
    {
        $customer = $this->database->getCustomerByEmail($email);
        if ($customer) {
            throw new CustomerAlreadyExistsException();
        }
        if (!EmailHelper::isLengthOK($email)) {
            throw new CustomersEmailIsTooLongException();
        }
        if (!EmailHelper::isFormatOK($email)) {
            throw new BadFormatOfCustomersEmailException();
        }

        return true;
    }

    public function validateFirstname($firstname)
    {
        if (!StringHelper::checkLength($firstname,
                            ConstantHelper::MIN_LENGTH_OF_FIRSTNAME,
                            ConstantHelper::MAX_LENGTH_OF_FIRSTNAME)) {
            throw new IncorrectLengthOfCustomersFirstname();
        }

        return true;
    }

    public function validateLastname($lastname)
    {
        if (!StringHelper::checkLength($lastname,
            ConstantHelper::MIN_LENGTH_OF_LASTNAME,
            ConstantHelper::MAX_LENGTH_OF_LASTNAME)) {
            throw new IncorrectLengthOfCustomersLastname();
        }

        return true;
    }

    public function validatePhone($phoneNumber)
    {
        if (!PhoneHelper::isFormatOK($phoneNumber)) {
            throw new IncorrectFormatOfCustomersPhone();
        }
    }

    public function insertCustomer($customerData) {
        $lastId = $this->database->insertCustomer($customerData);

        $customerData['id'] = $lastId;
        return new Customer($customerData);
    }
}
