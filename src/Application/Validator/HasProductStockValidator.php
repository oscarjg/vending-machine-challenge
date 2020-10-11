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
    public function isValid(MachineState $machineState): bool
    {
        $isValid = true;
        $itemSelected = $machineState->getItemSelected();

        foreach ($machineState->getItems() as $item) {
            if ($item->getSelector() !== $itemSelected) {
                continue;
            }

            $isValid = $item->getQuantity() > 0;
        }

        return $isValid;
    }

    public function message(): string
    {
        return "There aren't enough stock for this product";
    }
}
