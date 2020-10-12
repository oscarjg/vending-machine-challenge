<?php

namespace Tests\Application\Validator;

use App\Application\Validator\HasProductSelectedValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
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
     * @throws InvalidInsertedCoinValueException
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
     * @throws InvalidInsertedCoinValueException
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
     * @throws InvalidInsertedCoinValueException
     */
    private function invalidMachineState(): MachineState
    {
        return new MachineState(
            "uuid",
            new CoinCollector([]),
            $this->defaultInventory(),
            $this->defaultChange(),
            null
        );
    }

    /**
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function validMachineState(): MachineState
    {
        return new MachineState(
            "uuid",
            new CoinCollector([]),
            $this->defaultInventory(),
            $this->defaultChange(),
            1
        );
    }

}
