<?php

namespace Tests\Application\Validator;

use App\Application\Validator\HasEnoughChangeValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\MachineState;
use Tests\AbstractTestCase;

/**
 * Class HasEnoughChangeValidatorTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Validator
 */
class HasEnoughChangeValidatorTest extends AbstractTestCase
{
    /**
     * @param CoinCollector $amount
     * @param CoinCollector $change
     *
     * @dataProvider invalidCases
     */
    public function testInvalidCases(CoinCollector $amount, CoinCollector $change)
    {
        $validator = new HasEnoughChangeValidator();

        $isValid = $validator->isValid(
            $this->machineState(
                $amount,
                $change,
                1
            )
        );

        $this->assertFalse($isValid);
    }

    /**
     * @param CoinCollector $amount
     * @param CoinCollector $change
     *
     * @dataProvider validCases
     */
    public function testValidCases(CoinCollector $amount, CoinCollector $change)
    {
        $validator = new HasEnoughChangeValidator();

        $isValid = $validator->isValid(
            $this->machineState(
                $amount,
                $change,
                1
            )
        );

        $this->assertTrue($isValid);
    }

    /**
     * @return CoinCollector[][]
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function invalidCases(): array
    {
        return [
            [
                $this->coinsCollector([
                    new Coin(1.00),
                    new Coin(1.00)
                ]),
                $this->coinsCollector([
                    new Coin(0.25),
                    new Coin(0.25),
                    new Coin(0.25),
                ]),
            ],
            [
                $this->coinsCollector([
                    new Coin(1.00),
                    new Coin(0.25)
                ]),
                $this->coinsCollector([
                    new Coin(0.05),
                    new Coin(0.05),
                ]),
            ],
        ];
    }

    /**
     * @return CoinCollector[][]
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function validCases(): array
    {
        return [
            [
                $this->coinsCollector([]),
                $this->coinsCollector([]),
            ],
            [
                $this->coinsCollector([
                    new Coin(1.00),
                    new Coin(1.00),
                ]),
                $this->coinsCollector([
                    new Coin(1.00),
                ]),
            ],
        ];
    }

    /**
     * @param CoinCollector $insertedCoins
     * @param CoinCollector $changeCoins
     * @param int $itemSelected
     *
     * @return MachineState
     */
    private function machineState(
        CoinCollector $insertedCoins,
        CoinCollector $changeCoins,
        int $itemSelected
    ): MachineState {
        return new MachineState(
            "uuid",
            $insertedCoins,
            $this->defaultInventory(),
            $changeCoins,
            $itemSelected
        );
    }

    /**
     * @param array $coins
     *
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function coinsCollector(array $coins): CoinCollector
    {
        return new CoinCollector($coins);
    }
}
