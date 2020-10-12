<?php

namespace Tests\Application\UseCase;

use App\Application\UseCase\ReturnMoneyUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use Tests\AbstractTestCase;

/**
 * Class InsertMoneyUseCaseTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\UseCase
 */
class ReturnMoneyUseCaseTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testUseCase()
    {
        $state = $this->initialState();

        $useCase = new ReturnMoneyUseCase(
            $this->machineUuidGenerator()
        );

        $state = $useCase($state);

        $this->assertEmpty($state->getInsertedCoins());
    }
}
