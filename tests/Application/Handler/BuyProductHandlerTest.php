<?php

namespace Tests\Application\Handler;

use App\Application\Handler\BuyProductHandler;
use App\Application\Service\ExchangeService;
use App\Application\UseCase\BuyProductUseCase;
use App\Application\Validator\HasEnoughAmountValidator;
use App\Application\Validator\HasEnoughChangeValidator;
use App\Application\Validator\HasProductSelectedValidator;
use App\Application\Validator\HasProductStockValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\MachineState;
use App\Domain\VendingMachine\Model\Product;
use Tests\AbstractTestCase;

/**
 * Class BuyProductHandlerTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Service
 */
class BuyProductHandlerTest extends AbstractTestCase
{
    /**
     * @param MachineState $machineState
     * @param int $expectedCountErrors
     *
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     *
     * @dataProvider statesForErrors
     */
    public function testErrorsExpected(MachineState $machineState, int $expectedCountErrors)
    {
        $handler = new BuyProductHandler(
            new BuyProductUseCase(
                $this->machineUuidGenerator(),
            ),
            $this->currentMachineStateService($machineState),
            $this->invalidators(),
            new ExchangeService(),
            $this->machineStateRepository()
        );

        $response = $handler();

        $this->assertFalse($response->isValid());
        $this->assertCount($expectedCountErrors, $response->getErrors());
    }

    /**
     * @param MachineState $machineState
     * @param int $exchangeExpected
     *
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     *
     * @dataProvider statesForSuccess
     */
    public function testSuccessExpected(MachineState $machineState, int $exchangeExpected)
    {
        $handler = new BuyProductHandler(
            new BuyProductUseCase(
                $this->machineUuidGenerator(),
            ),
            $this->currentMachineStateService($machineState),
            $this->invalidators(),
            new ExchangeService(),
            $this->machineStateRepository()
        );

        $response = $handler();

        $this->assertTrue($response->isValid());
        $this->assertCount(0, $response->getErrors());
        $this->assertEquals($exchangeExpected, $response->getExchange()->sum());
    }

    /**
     * @return MachineState[]
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function statesForSuccess(): array
    {
        return [
//            [
//                new MachineState(
//                    "uuid",
//                    new CoinCollector([
//                        new Coin(25),
//                        new Coin(25),
//                        new Coin(25),
//                        new Coin(25),
//                    ]),
//                    $this->defaultInventory(),
//                    $this->defaultChange(),
//                    1
//                ),
//                0
//            ],
//            [
//                new MachineState(
//                    "uuid",
//                    new CoinCollector([
//                        new Coin(25),
//                        new Coin(25),
//                        new Coin(25),
//                        new Coin(25),
//                        new Coin(5),
//                    ]),
//                    $this->defaultInventory(),
//                    $this->defaultChange(),
//                    1
//                ),
//                5
//            ]
            [
                new MachineState(
                    "uuid",
                    new CoinCollector([
                        new Coin(10),
                        new Coin(10),
                        new Coin(10),
                        new Coin(10),
                        new Coin(10),
                        new Coin(10),
                        new Coin(10),

                    ]),
                    $this->defaultInventory(),
                    $this->defaultChange(),
                    2
                ),
                5
            ]
        ];
    }

    /**
     * @return MachineState[]
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function statesForErrors(): array
    {
        return [
            [
                new MachineState(
                    "uuid",
                    new CoinCollector([]),
                    $this->defaultInventory(),
                    $this->defaultChange(),
                    null
                ),
                4
            ],
            [
                new MachineState(
                    "uuid",
                    new CoinCollector([]),
                    $this->defaultInventory(),
                    $this->defaultChange(),
                    3
                ),
                1
            ],
            [
                new MachineState(
                    "uuid",
                    new CoinCollector([]),
                    new Inventory([
                        new Item(new Product("p1", 100), 0, 1),
                    ]),
                    $this->defaultChange(),
                    1
                ),
                2
            ],
            [
                new MachineState(
                    "uuid",
                    new CoinCollector([
                        new Coin(100)
                    ]),
                    new Inventory([
                        new Item(new Product("p1", 100), 0, 1),
                    ]),
                    $this->defaultChange(),
                    1
                ),
                1
            ],
            [
                new MachineState(
                    "uuid",
                    new CoinCollector([
                        new Coin(100),
                        new Coin(100)
                    ]),
                    new Inventory([
                        new Item(new Product("p1", 100), 10, 1),
                    ]),
                    new CoinCollector([
                        new Coin(25)
                    ]),
                    1
                ),
                1
            ]
        ];
    }

    /**
     * @return array
     */
    private function invalidators(): array
    {
        return [
            new HasProductSelectedValidator(),
            new HasProductStockValidator(),
            new HasEnoughAmountValidator(),
            new HasEnoughChangeValidator(),
        ];
    }
}
