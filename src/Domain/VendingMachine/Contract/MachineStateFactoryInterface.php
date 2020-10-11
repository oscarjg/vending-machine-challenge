<?php

namespace App\Domain\VendingMachine\Contract;

use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Interface MachineStateFactoryInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Contract
 */
interface MachineStateFactoryInterface
{
    /**
     * @param string $uuid
     * @param InsertedCoins $insertedCoins
     * @param Inventory $inventory
     * @param int|null $itemSelected
     *
     * @return MachineState
     */
    public static function createMachineState(
        string $uuid,
        InsertedCoins $insertedCoins,
        Inventory $inventory,
        ?int $itemSelected
    ): MachineState;
}
