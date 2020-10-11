<?php

namespace App\Domain\VendingMachine\Model;

/**
 * Class Product
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Model
 */
class Product
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var float
     */
    protected float $price;

    /**
     * @var string
     */
    protected string $name;

    /**
     * Product constructor.
     *
     * @param string $name
     * @param float $price
     */
    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
