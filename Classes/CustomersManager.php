<?php
namespace Classes;

use Classes\Logic\CustomersLogic;

class CustomersManager
{
    /** @var Database */
    protected $database;

    /** @var CustomersLogic */
    protected $userLogic;

    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->userLogic = CustomersLogic::getInstance();
    }

    public function createNewUser($data)
    {
        $this->userLogic->validateEmail($data['email']);
        $this->userLogic->validateFirstname($data['firstname']);
        $this->userLogic->validateLastname($data['lastname']);
        $this->userLogic->validatePhone($data['phone']);

        $this->userLogic->insertCustomer($data);
    }
}