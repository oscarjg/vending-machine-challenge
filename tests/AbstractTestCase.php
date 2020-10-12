<?php

namespace Tests;

use App\Application\Service\CurrentMachineState;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
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
            $coins[] = new Coin(5);
            $coins[] = new Coin(10);
            $coins[] = new Coin(25);
            $coins[] = new Coin(100);
        }

        return new CoinCollector($coins);
    }

    /**
     * @return Inventory
     */
    protected function defaultInventory(): Inventory
    {
        return new Inventory([
            new Item(new Product("p1", 100), 10, 1),
            new Item(new Product("p2", 65), 20, 2),
            new Item(new Product("p3", 150), 30, 3),
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
    protected function defaultItemSelector(): int
    {
        return 1;
    }

    /**
     * @return MachineStateUuidGeneratorInterface
     */
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

    /**
     * @return MachineStateRepository
     */
    protected function machineStateRepository(): MachineStateRepository
    {
        $mock = $this
            ->getMockBuilder(MachineStateRepository::class)
            ->getMock();

        $mock
            ->method('saveState')
            ->willReturnCallback(function (MachineState $machineState) {
                return $machineState;
            });

        return $mock;
    }

    /**
     * @param MachineState $machineState
     *
     * @return CurrentMachineState
     */
    protected function currentMachineStateService(MachineState $machineState): CurrentMachineState
    {
        $mock = $this
            ->getMockBuilder(CurrentMachineState::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->method('__invoke')
            ->willReturnCallback(function () use ($machineState) {
                return $machineState;
            });

        return $mock;
    }
}
