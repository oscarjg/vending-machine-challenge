<?php

namespace Tests\Application\UseCase;

use App\Application\Service\ExchangeService;
use App\Application\UseCase\BuyProductUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\MachineState;
use Tests\AbstractTestCase;

/**
 * Class BuyProductUseCaseTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\UseCase
 */
class BuyProductUseCaseTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testUseCase()
    {
        $useCase = new BuyProductUseCase(
            $this->machineUuidGenerator()
        );

        $state = new MachineState(
            "uuid",
            new CoinCollector([
                new Coin(100)
            ]),
            $this->defaultInventory(),
            $this->defaultChange(),
            2
        );

        $this->assertEquals(1400, $state->totalChange());

        $state = $useCase(
            $state,
            new ExchangeService()
        );

        $this->assertEquals(1465, $state->totalChange());

        $items = array_filter($state->getItems(), function (Item $item) {
            return $item->getSelector() === 2;
        });

        $this->assertEquals(19, $items[1]->getQuantity());
    }
}
