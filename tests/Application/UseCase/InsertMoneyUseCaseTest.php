<?php

namespace Tests\Application\UseCase;

use App\Application\UseCase\InsertMoneyUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\VendingMachine\Model\Coin;
use Tests\AbstractTestCase;

/**
 * Class InsertMoneyUseCaseTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\UseCase
 */
class InsertMoneyUseCaseTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testUseCase()
    {
        $state = $this->initialState();
        $useCase = new InsertMoneyUseCase(
            $this->machineUuidGenerator()
        );
        $coins = [25, 25, 100];

        foreach ($coins as $key => $coinValue) {
            $state = $useCase(
                $state,
                $coinValue
            );

            /**
             * @var Coin $expectedCoin
             */
            $expectedCoin = $state->getInsertedCoins()[$key];

            $this->assertEquals(
                $expectedCoin->getValue(),
                $coinValue
            );
        }
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testUseCaseThrowCorrectException()
    {
        $this->expectException(InvalidInsertedCoinValueException::class);

        $state = $this->initialState();
        $useCase = new InsertMoneyUseCase(
            $this->machineUuidGenerator()
        );

        $useCase->__invoke(
            $state,
            15
        );
    }
}
