<?php

namespace App\Domain\VendingMachine\Contract;

use App\Domain\VendingMachine\Model\MachineState;

/**
 * Interface MachineStateRepository
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Contract
 */
interface MachineStateRepository
{
    public function saveState(MachineState $machineState): MachineState;
    public function fetchCurrentState(): ?MachineState;
}
