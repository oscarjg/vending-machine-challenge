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
    public function isValid(MachineState $machineState): bool
    {
        $isValid = true;
        $itemSelected = $machineState->getItemSelected();
        $amount = $machineState->totalAmount();

        foreach ($machineState->getItems() as $item) {
            if ($item->getSelector() !== $itemSelected) {
                continue;
            }

            $isValid = $item->getProduct()->getPrice() <= $amount;
            break;
        }

        return $isValid;
    }

    public function message(): string
    {
        return "There aren't enough coins to buy this product";
    }
}
