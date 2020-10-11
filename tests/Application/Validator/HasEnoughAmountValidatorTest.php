<?php

namespace Tests\Application\Validator;

use App\Application\Validator\HasEnoughAmountValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
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
     * @throws InvalidInsertedCoinValueException
     */
    public function testEmptyAmount()
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
     * @throws InvalidInsertedCoinValueException
     */
    public function testNotEnoughAmount()
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
     * @throws InvalidInsertedCoinValueException
     */
    public function testExactAmount()
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
     * @throws InvalidInsertedCoinValueException
     */
    public function testEnoughAmount()
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
     * @param CoinCollector $insertedCoins
     * @param int $itemSelected
     *
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function machineState(CoinCollector $insertedCoins, int $itemSelected): MachineState
    {
        return new MachineState(
            "uuid",
            $insertedCoins,
            $this->defaultInventory(),
            $this->emptyCoinsCollector(),
            $itemSelected
        );
    }

    /**
     * @param array $coins
     *
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws \App\Domain\Exceptions\InvalidInsertedCoinValueException
     */
    private function insertedCoins(array $coins): CoinCollector
    {
        return new CoinCollector($coins);
    }
}
