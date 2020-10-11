<?php

namespace App\Domain\VendingMachine\Model;

use App\Domain\ValueObjects\InsertedCoins;

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
     * MachineState constructor.
     *
     * @param string $uuid
     * @param InsertedCoins $insertedCoins
     */
    public function __construct(
        string $uuid,
        InsertedCoins $insertedCoins
    ) {
        $this->uuid = $uuid;
        $this->insertedCoins = $insertedCoins->getCoins();
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
}
