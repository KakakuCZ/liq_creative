<?php
namespace Classes\Objects;

class ProductType
{
    protected $id;
    protected $name;
    protected $items = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addItem(Product $product)
    {
        if (isset($items[$product->getId()])) {
            throw new \InvalidArgumentException('This item is already exists! Id: ' . $product->getId());
        }

        $this->items[] = $product;
    }

    public function getItems(): array
    {
        return $this->items;
    }


}
