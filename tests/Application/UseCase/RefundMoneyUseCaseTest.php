<?php

namespace Tests\Application\UseCase;

use App\Application\UseCase\InsertMoneyUseCase;
use App\Application\UseCase\RefundMoneyUseCase;
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
class RefundMoneyUseCaseTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testUseCase()
    {
        $state = $this->initialState();

        $useCase = new RefundMoneyUseCase(
            $this->machineUuidGenerator()
        );

        $state = $useCase($state);

        $this->assertEmpty($state->getInsertedCoins());
    }
}
