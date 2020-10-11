<?php

namespace Tests\Application\Validator;

use App\Application\Validator\HasEnoughAmountValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\MachineState;
use Tests\AbstractTestCase;

/**
 * Class HasEnoughAmountValidatorTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Validator
 */
class HasEnoughAmountValidatorTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testEmptyBalance()
    {
        $validator = new HasEnoughAmountValidator();

        $isValid = $validator->isValid(
            $this->machineState(
                $this->insertedCoins([]),
                1
            )
        );

        $this->assertFalse($isValid);
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testNotEnoughBalance()
    {
        $validator = new HasEnoughAmountValidator();

        $isValid = $validator->isValid(
            $this->machineState(
                $this->insertedCoins([
                    new Coin(0.25),
                    new Coin(0.25)
                ]),
                1
            )
        );

        $this->assertFalse($isValid);
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testExactBalance()
    {
        $validator = new HasEnoughAmountValidator();

        $isValid = $validator->isValid(
            $this->machineState(
                $this->insertedCoins([
                    new Coin(1.00)
                ]),
                1
            )
        );

        $this->assertTrue($isValid);
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testEnoughBalance()
    {
        $validator = new HasEnoughAmountValidator();

        $isValid = $validator->isValid(
            $this->machineState(
                $this->insertedCoins([
                    new Coin(0.25),
                    new Coin(1.00)
                ]),
                1
            )
        );

        $this->assertTrue($isValid);
    }

    /**
     * @param InsertedCoins $insertedCoins
     * @param int $itemSelected
     *
     * @return MachineState
     */
    private function machineState(InsertedCoins $insertedCoins, int $itemSelected): MachineState
    {
        return new MachineState(
            "uuid",
            $insertedCoins,
            $this->defaultInventory(),
            $itemSelected
        );
    }

    /**
     * @param array $coins
     *
     * @return InsertedCoins
     * @throws InvalidInsertedCoinInstanceException
     */
    private function insertedCoins(array $coins): InsertedCoins
    {
        return new InsertedCoins($coins);
    }
}
