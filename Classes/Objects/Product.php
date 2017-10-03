<?php
namespace Classes\Objects;

class Product
{
    protected $id;
    protected $product_type_id;
    protected $product_type_name;
    protected $name;
    protected $price_buy;
    protected $price_sell;

    protected $isChecked = false;
    protected $isDisplayed = true;

    public function setChecked(bool $value)
    {
        $this->isChecked = $value;
    }

    public function setDisplayed(bool $value)
    {
        $this->isDisplayed = $value;
    }



    public function getId(): int
    {
        return $this->id;
    }

    public function getProductTypeId(): int
    {
        return $this->product_type_id;
    }

    public function getProductTypeName(): string
    {
        return $this->product_type_name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriceBuy(): float
    {
        return $this->price_buy;
    }

    public function getPriceSell(): float
    {
        return $this->price_sell;
    }

    public function isChecked(): bool
    {
        return $this->isChecked;
    }

    public function isDisplayed(): bool
    {
        return $this->isDisplayed;
    }





}
