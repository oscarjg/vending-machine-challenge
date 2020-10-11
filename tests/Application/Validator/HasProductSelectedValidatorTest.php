<?php

namespace Tests\Application\Validator;

use App\Application\Validator\HasProductSelectedValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\VendingMachine\Model\MachineState;
use Tests\AbstractTestCase;

/**
 * Class HasProductSelectedValidatorTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Validator
 */
class HasProductSelectedValidatorTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testIsNotValid()
    {
        $validator = new HasProductSelectedValidator();

        $isValid = $validator->isValid(
            $this->invalidMachineState()
        );

        $this->assertFalse($isValid);
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testIsValid()
    {
        $validator = new HasProductSelectedValidator();

        $isValid = $validator->isValid(
            $this->validMachineState()
        );

        $this->assertTrue($isValid);
    }

    /**
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     */
    private function invalidMachineState(): MachineState
    {
        return new MachineState(
            "uuid",
            new InsertedCoins([]),
            $this->defaultInventory(),
            null
        );
    }

    /**
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     */
    private function validMachineState(): MachineState
    {
        return new MachineState(
            "uuid",
            new InsertedCoins([]),
            $this->defaultInventory(),
            1
        );
    }

}
