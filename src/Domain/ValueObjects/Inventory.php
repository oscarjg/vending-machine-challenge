<?php

namespace App\Domain\ValueObjects;

use App\Domain\VendingMachine\Model\Item;

/**
 * Class Inventory
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\ValueObjects
 */
class Inventory
{
    /**
     * @var Item[]
     */
    protected iterable $items;

    /**
     * Inventory constructor.
     *
     * @param iterable $items
     */
    public function __construct(iterable $items)
    {
        $this->items = $items;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
