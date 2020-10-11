<?php

namespace App\Domain\VendingMachine\Contract;

use App\Domain\ValueObjects\InsertedCoins;
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
     *
     * @return MachineState
     */
    public static function createMachineState(
        string $uuid,
        InsertedCoins $insertedCoins
    ): MachineState;
}
