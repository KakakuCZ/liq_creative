<?php
namespace Classes\Objects;

class Customer {
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $phone;


    public function __construct($data)
    {
        $this->setId($data['id']);
        $this->setFirstname($data['firstname']);
        $this->setLastname($data['lastname']);
        $this->setPhone($data['phone_number']);
    }

    public function getFullname() :string
    {
        return $this->getLastname() . ' ' . $this->getFirstname();
    }

    public function getId(): float
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }




}
