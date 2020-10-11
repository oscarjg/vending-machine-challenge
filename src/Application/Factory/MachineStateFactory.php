<?php

namespace App\Application\Factory;

use App\Domain\ValueObjects\InsertedCoins;
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
     *
     * @return MachineState
     */
    public static function createMachineState(
        string $uuid,
        InsertedCoins $insertedCoins
    ): MachineState {
        return new MachineState(
            $uuid,
            $insertedCoins
        );
    }
}
