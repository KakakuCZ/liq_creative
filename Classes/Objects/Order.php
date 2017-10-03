<?php
namespace Classes\Objects;

class Order
{
    protected $fullArray;

    protected $parts;

    public function __construct(array $productTypesWithProducts)
    {
        $this->fullArray = $productTypesWithProducts;
        /** @var ProductType $productType */
        foreach ($productTypesWithProducts as $productType) {
            switch($productType->getName()) {
                case 'base media':
                    $this->parts['base_media'] = $productType;
                    break;
                case 'print media':
                    $this->parts['print_media'] = $productType;
                    break;
                case 'finishing':
                    $this->parts['finishing'] = $productType;
                    break;
            }
        }
    }

    public function getPartsForForm(): array
    {
        return $this->parts;
    }

}
