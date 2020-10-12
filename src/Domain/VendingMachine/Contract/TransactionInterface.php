<?php

namespace App\Domain\VendingMachine\Contract;

use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Interface TransactionInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Contract
 */
interface TransactionInterface
{
    public function run(MachineState $machineState): VendingMachineResponse;
}
