<?php

namespace App\Application\Validator;

use App\Domain\VendingMachine\Contract\VendingMachineValidatorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class HasProductStockValidator
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Validator
 */
class HasProductStockValidator implements VendingMachineValidatorInterface
{
    /**
     * @param MachineState $machineState
     *
     * @return bool
     */
    public function isValid(MachineState $machineState): bool
    {
        $itemSelected = $machineState->itemSelected();

        if ($itemSelected === null) {
            return false;
        }

        return $itemSelected->getQuantity() > 0;
    }

    public function message(): string
    {
        return "There aren't enough stock for this product";
    }
}
