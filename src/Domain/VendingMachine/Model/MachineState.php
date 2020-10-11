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
        0.05,
        0.10,
        0.25,
        1.00,
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
     * @var iterable
     */
    protected iterable $insertedCoins;

    /**
     * @var ?int
     */
    protected ?int $itemSelector;

    /**
     * @var Item[]
     */
    protected iterable $items;

    /**
     * @var iterable
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
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return iterable
     */
    public function getChange(): iterable
    {
        return $this->change;
    }

    /**
     * @return mixed
     */
    public function totalAmount(): float
    {
        return array_reduce($this->insertedCoins, function (int $acc, Coin $coin) {
            return $acc + $coin->getValue();
        }, 0);
    }

    /**
     * @return mixed
     */
    public function totalChange(): float
    {
        return array_reduce($this->change, function (int $acc, Coin $coin) {
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

        return  $item->getProduct();
    }
}
