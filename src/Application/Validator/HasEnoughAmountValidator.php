<?php

namespace App\Application\Validator;

use App\Domain\VendingMachine\Contract\VendingMachineValidatorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class HasEnoughAmountValidator
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Validator
 */
class HasEnoughAmountValidator implements VendingMachineValidatorInterface
{
    /**
     * @param MachineState $machineState
     *
     * @return bool
     */
    public function isValid(MachineState $machineState): bool
    {
        if ($machineState->productSelected() === null) {
            return false;
        }

        $amount = $machineState->totalAmount();

        return $machineState->productSelected()->getPrice() <= $amount;
    }

    public function message(): string
    {
        return "The total amount inserted it's not enough. Please insert coins to buy a product";
    }
}
