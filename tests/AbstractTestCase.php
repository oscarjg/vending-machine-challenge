<?php

namespace Tests;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
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
     */
    protected function initialState(): MachineState
    {
        return new MachineState(
            "uuid",
            new InsertedCoins([]),
            $this->defaultInventory()
        );
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
     * @return InsertedCoins
     * @throws InvalidInsertedCoinInstanceException
     */
    protected function emptyInsertedCoins(): InsertedCoins
    {
        return new InsertedCoins([]);
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
