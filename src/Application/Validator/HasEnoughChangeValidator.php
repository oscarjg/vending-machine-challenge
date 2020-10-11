<?php

namespace App\Application\Validator;

use App\Domain\VendingMachine\Contract\VendingMachineValidatorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class HasEnoughChangeValidator
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Validator
 */
class HasEnoughChangeValidator implements VendingMachineValidatorInterface
{
    /**
     * @param MachineState $machineState
     *
     * @return bool
     */
    public function isValid(MachineState $machineState): bool
    {
        $product = $machineState->productSelected();
        $amount  = $machineState->totalAmount();
        $change  = $machineState->totalChange();

        if ($product === null) {
            return false;
        }

        $exchange = $amount - $product->getPrice();

        return $change >= $exchange;
    }

    public function message(): string
    {
        return "There aren't enough exchange. Please insert exact amount";
    }
}
