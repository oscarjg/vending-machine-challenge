<?php

namespace Tests;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\MachineState;
use App\Domain\VendingMachine\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    protected function initialState(): MachineState
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
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    protected function defaultChange()
    {
        /**
         * @var Coin[]
         */
        $coins = [];

        for ($x = 0; $x < 10; $x++) {
            $coins[] = new Coin(0.05);
            $coins[] = new Coin(0.10);
            $coins[] = new Coin(0.25);
            $coins[] = new Coin(1.00);
        }

        return new CoinCollector($coins);
    }

    /**
     * @return Inventory
     */
    protected function defaultInventory(): Inventory
    {
        return new Inventory([
            new Item(new Product("p1", 1.00), 10, 1),
            new Item(new Product("p2", 0.65), 20, 2),
            new Item(new Product("p3", 1.50), 30, 3),
        ]);
    }

    /**
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws \App\Domain\Exceptions\InvalidInsertedCoinValueException
     */
    protected function emptyCoinsCollector(): CoinCollector
    {
        return new CoinCollector([]);
    }

    /**
     * @return Inventory
     */
    protected function emptyInventory(): Inventory
    {
        return new Inventory([]);
    }

    /**
     * @return int
     */
    protected function defaultItemSelected(): int
    {
        return 1;
    }

    protected function machineUuidGenerator(): MachineStateUuidGeneratorInterface
    {
        $mock = $this
            ->getMockBuilder(MachineStateUuidGeneratorInterface::class)
            ->getMock();

        $mock
            ->method('generate')
            ->willReturn("foo-uuid");

        return $mock;
    }
}
