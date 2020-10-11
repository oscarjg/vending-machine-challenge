<?php

namespace App\Domain\VendingMachine\Model;

/**
 * Class MachineState
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Model
 */
class MachineState
{
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
     * @param iterable $insertedCoins
     */
    public function __construct(string $uuid, iterable $insertedCoins)
    {
        $this->uuid = $uuid;
        $this->insertedCoins = $insertedCoins;
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
