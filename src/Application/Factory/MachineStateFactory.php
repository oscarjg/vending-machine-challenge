<?php

namespace App\Application\Factory;

use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateFactoryInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class MachineStateFactory
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Factory
 */
class MachineStateFactory implements MachineStateFactoryInterface
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
        ?int $itemSelected = null
    ): MachineState {
        return new MachineState(
            $uuid,
            $insertedCoins,
            $inventory,
            $itemSelected
        );
    }
}
