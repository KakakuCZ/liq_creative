<?php
namespace Classes\Objects;

class Customer {
    protected $id;
    protected $firstname;
    protected $lastname;


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


}
