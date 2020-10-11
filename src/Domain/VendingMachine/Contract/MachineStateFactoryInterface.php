<?php

namespace App\Domain\VendingMachine\Contract;

use App\Domain\ValueObjects\CoinCollector;
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
     * @param CoinCollector $insertedCoins
     * @param Inventory $inventory
     * @param CoinCollector $change
     * @param int|null $itemSelected
     *
     * @return MachineState
     */
    public static function createMachineState(
        string $uuid,
        CoinCollector $insertedCoins,
        Inventory $inventory,
        CoinCollector $change,
        ?int $itemSelected
    ): MachineState;
}
