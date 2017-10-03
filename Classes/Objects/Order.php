<?php
namespace Classes\Objects;

use Classes\Exceptions\Forms\InvalidSizeException;

class Order
{
    const PRICE_SUPPLIER_SHIPING = 10.50;
    const PRICE_INK = 14.00; //per square meter
    const PRICE_LABOUR = 30.00; //per hour


    const ROLE_WIDTH = 1220 / 1000; //In metres

    protected $initilized = false;

    /** Base structure */

    protected $fullArray;

    protected $parts;


    /** Concrete parts */

    protected $width;
    protected $length;

    protected $squareMetres;
    protected $roleMetres;



    /** @var  Product|Null */
    protected $baseMedia;

    /** @var  Product|Null */
    protected $printMedia;

    /** @var  Product|Null */
    protected $finishing;

    protected $shipping;



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

    /** Inputs must be array and base, print media + finish must be object */
    public function setItems(array $inputs)
    {
        $allowedNames = [
            'width',
            'length',
            'baseMedia',
            'printMedia',
            'finishing',
            'shipping'
        ];
        foreach ($inputs as $inputName => $inputValue) {
            if (!in_array($inputName, $allowedNames)) {
                throw new \InvalidArgumentException();
            }
            $this->{'set' . ucfirst($inputName)}($inputValue);
        }

        $this->squareMetres = $this->width * $this->length;
        $this->roleMetres = $this->countRoleMetres();

        if ($this->initilized === FALSE) {
            $this->setInitialized(TRUE);
        }
    }

    protected function setInitialized($value)
    {
        $this->initilized = $value;
    }


    public function setBaseMedia(?Product $baseMedia)
    {
        $this->baseMedia = $baseMedia;
    }

    public function setPrintMedia(?Product $printMedia)
    {
        $this->printMedia = $printMedia;
    }

    public function setFinishing(?Product $finishing)
    {
        $this->finishing = $finishing;
    }

    public function setWidth($width) //:int
    {
        //save in metres
        $this->width = $width / 1000;
    }

    public function setLength($length) //:int
    {
        //save in metres
        $this->length = $length / 1000;
    }

    public function setShipping($shipping) //:bool
    {
        $this->shipping = $shipping;
    }

    private function countRoleMetres()
    {
        if ($this->width > self::ROLE_WIDTH && $this->length > self::ROLE_WIDTH) {
            throw new InvalidSizeException();
        }

        if ($this->width < self::ROLE_WIDTH && $this->length < self::ROLE_WIDTH) {
            if ($this->width > $this->length) {
                $roleMetres = $this->length;
            } else {
                $roleMetres = $this->width;
            }
        } else {
            if ($this->width > $this->length) {
                $roleMetres = $this->width;
            } else {
                $roleMetres = $this->length;
            }
        }

        return $roleMetres;
    }


    public function getPrice()
    {
        $totalPrice = 0;

        //Base Media
        if ($this->baseMedia != null) {
            $totalPrice += $this->getBaseMediaPrice();
        }

        //Print Media
        if ($this->printMedia != null) {
            $totalPrice += $this->getPrintMediaPrice();
        }

        //Ink
        $totalPrice += $this->getInkPrice();

        //Finishing
        if ($this->finishing != null) {
            $totalPrice += $this->getFinishingPrice();
        }

        //Labour
        $totalPrice += $this->getLabourPrice();

        //Supplier Shipping
        if ($this->shipping === TRUE) {
            $totalPrice += $this->getShippingPrice();
        }

        return $totalPrice;
    }

    public function getBaseMediaPrice(): float
    {
        return $this->baseMedia->getPriceSell() * $this->roleMetres;
    }

    public function getPrintMediaPrice(): float
    {
        return $this->printMedia->getPriceSell() * ($this->roleMetres + 0.25);
    }

    public function getInkPrice(): float
    {
        return self::PRICE_INK * $this->squareMetres;
    }

    public function getFinishingPrice(): float
    {
         return $this->finishing->getPriceSell() * $this->squareMetres;
    }

    public function getLabourPrice(): float
    {
        //TODO: Count hours by some logic. Now is allways 1 hour
        return 1 * self::PRICE_LABOUR;
    }

    public function getShippingPrice() :float
    {
        return self::PRICE_SUPPLIER_SHIPING;
    }
}
