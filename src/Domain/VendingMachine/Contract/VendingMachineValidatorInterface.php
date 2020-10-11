<?php

namespace App\Domain\VendingMachine\Contract;

use App\Domain\VendingMachine\Model\MachineState;

/**
 * Interface VendingMachineValidatorInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Contract
 */
interface VendingMachineValidatorInterface
{
    public function isValid(MachineState $machineState): bool;
    public function message(): string;
}
