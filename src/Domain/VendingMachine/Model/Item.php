<?php

namespace App\Domain\VendingMachine\Model;

/**
 * Class Item
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Model
 */
class Item
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var Product
     */
    protected Product $product;

    /**
     * @var int
     */
    protected int $quantity;

    /**
     * @var int
     */
    protected int $selector;

    /**
     * Item constructor.
     *
     * @param Product $product
     * @param int $quantity
     * @param int $selector
     */
    public function __construct(
        Product $product,
        int $quantity,
        int $selector
    ) {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->selector = $selector;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getSelector(): int
    {
        return $this->selector;
    }
}
