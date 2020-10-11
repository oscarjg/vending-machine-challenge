<?php

namespace App\Domain\VendingMachine\Model;

use App\Domain\ValueObjects\InsertedCoins;
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
    protected ?int $itemSelected;

    /**
     * @var Item[]
     */
    protected iterable $items;

    /**
     * MachineState constructor.
     *
     * @param string $uuid
     * @param InsertedCoins $insertedCoins
     * @param Inventory $inventory
     * @param int|null $itemSelected
     */
    public function __construct(
        string $uuid,
        InsertedCoins $insertedCoins,
        Inventory $inventory,
        ?int $itemSelected = null
    ) {
        $this->uuid = $uuid;
        $this->insertedCoins = $insertedCoins->getCoins();
        $this->items = $inventory->getItems();
        $this->itemSelected = $itemSelected;
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
    public function getItemSelected(): ?int
    {
        return $this->itemSelected;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
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
}
