<?php

namespace App\Domain\VendingMachine\Model;

use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;

/**
 * Class MachineState
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Model
 */
class MachineState
{
    public const ACCEPTED_COINS = [
        5,
        10,
        25,
        100,
    ];

    /**
     * @var int
     */
    protected int $id;

    /**
     * @var string
     */
    protected string $uuid;

    /**
     * @var Coin[]
     */
    protected iterable $insertedCoins;

    /**
     * @var ?int
     */
    protected ?int $itemSelector = null;

    /**
     * @var Item[]
     */
    protected iterable $items;

    /**
     * @var Coin[]
     */
    protected iterable $change;

    /**
     * MachineState constructor.
     *
     * @param string $uuid
     * @param CoinCollector $insertedCoins
     * @param Inventory $inventory
     * @param CoinCollector $change
     * @param int|null $itemSelector
     */
    public function __construct(
        string $uuid,
        CoinCollector $insertedCoins,
        Inventory $inventory,
        CoinCollector $change,
        ?int $itemSelector = null
    ) {
        $this->uuid = $uuid;
        $this->insertedCoins = $insertedCoins->getCoins();
        $this->items = $inventory->getItems();
        $this->change = $change->getCoins();
        $this->itemSelector = $itemSelector;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return iterable
     */
    public function getInsertedCoins(): iterable
    {
        if (is_object($this->insertedCoins)) {
            return $this->insertedCoins->toArray();
        }

        return $this->insertedCoins;
    }

    /**
     * @return int|null
     */
    public function getItemSelector(): ?int
    {
        return $this->itemSelector;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        if (is_object($this->items)) {
            return $this->items->toArray();
        }

        return $this->items;
    }

    /**
     * @return iterable
     */
    public function getChange(): iterable
    {
        if (is_object($this->change)) {
            return $this->change->toArray();
        }

        return $this->change;
    }

    /**
     * @return mixed
     */
    public function totalAmount(): float
    {
        return array_reduce($this->getInsertedCoins(), function (int $acc, Coin $coin) {
            return $acc + $coin->getValue();
        }, 0);
    }

    /**
     * @return mixed
     */
    public function totalChange(): float
    {
        return array_reduce($this->getChange(), function (int $acc, Coin $coin) {
            return $acc + $coin->getValue();
        }, 0);
    }

    /**
     * @return Item|null
     */
    public function itemSelected(): ?Item
    {
        $itemSelected = null;

        foreach ($this->items as $item) {
            if ($item->getSelector() !== $this->itemSelector) {
                continue;
            }

            $itemSelected = $item;
            break;
        }

        return $itemSelected;
    }

    /**
     * @return Product|null
     */
    public function productSelected(): ?Product
    {
        $product = null;

        $item = $this->itemSelected();

        if ($item === null) {
            return null;
        }

        return $item->getProduct();
    }
}
