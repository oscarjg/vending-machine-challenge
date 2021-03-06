<?php

namespace App\Application\Validator;

use App\Domain\VendingMachine\Contract\VendingMachineValidatorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class HasProductSelectedValidator
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Validator
 */
class HasProductSelectedValidator implements VendingMachineValidatorInterface
{
    /**
     * @param MachineState $machineState
     *
     * @return bool
     */
    public function isValid(MachineState $machineState): bool
    {
        return $machineState->productSelected() !== null;
    }

    public function message(): string
    {
        return "Any product selected. Please select a product to buy something";
    }
}
