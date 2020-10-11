<?php

namespace Tests\Application\Factory;

use App\Application\Factory\MachineStateFactory;
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
 * Class MachineStateFactoryTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Factory
 */
class MachineStateFactoryTest extends AbstractTestCase
{
    /**
     * @param string $uuid
     * @param CoinCollector $insertedCoins
     * @param Inventory $inventory
     * @param CoinCollector $change
     * @param int|null $itemSelected
     *
     * @dataProvider data
     */
    public function testCreateMachineStateInstance(
        string $uuid,
        CoinCollector $insertedCoins,
        Inventory $inventory,
        CoinCollector $change,
        ?int $itemSelected
    ) {
        $machineState = MachineStateFactory::createMachineState(
            $uuid,
            $insertedCoins,
            $inventory,
            $change,
            $itemSelected
        );

        $this->assertInstanceOf(MachineState::class, $machineState);
        $this->assertEquals($uuid, $machineState->getUuid());
        $this->assertEquals($insertedCoins->getCoins(), $machineState->getInsertedCoins());
        $this->assertEquals($inventory->getItems(), $machineState->getItems());
        $this->assertEquals($itemSelected, $machineState->getItemSelector());
        $this->assertEquals($change->getCoins(), $machineState->getChange());
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testInvalidCoinsInserted()
    {
        $this->expectException(InvalidInsertedCoinInstanceException::class);

        MachineStateFactory::createMachineState(
            "some-uuid",
            new CoinCollector([
                new \stdClass(),
                new \stdClass(),
            ]),
            $this->emptyInventory(),
            $this->emptyCoinsCollector(),
            $this->defaultItemSelector()
        );
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testInvalidChange()
    {
        $this->expectException(InvalidInsertedCoinInstanceException::class);

        MachineStateFactory::createMachineState(
            "some-uuid",
            $this->emptyCoinsCollector(),
            $this->emptyInventory(),
            new CoinCollector([
                new \stdClass(),
                new \stdClass(),
            ]),
            $this->defaultItemSelector()
        );
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testInvalidCoinsValuesInserted()
    {
        $this->expectException(InvalidInsertedCoinValueException::class);

        MachineStateFactory::createMachineState(
            "some-uuid",
            new CoinCollector([
                new Coin(1.00),
                new Coin(1.50),
            ]),
            $this->emptyInventory(),
            $this->emptyCoinsCollector(),
            $this->defaultItemSelector()
        );
    }

    /**
     * @return array[]
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function data(): array
    {
        return [
            [
                "foo-id",
                $this->emptyCoinsCollector(),
                $this->emptyInventory(),
                $this->emptyCoinsCollector(),
                $this->defaultItemSelector()
            ],
            [
                "foo-id",
                new CoinCollector([
                    new Coin(1.00),
                    new Coin(1.00),
                    new Coin(0.25),
                ]),
                $this->defaultInventory(),
                new CoinCollector([
                    new Coin(1.00),
                    new Coin(1.00),
                    new Coin(1.00),
                ]),
                $this->defaultItemSelector()
            ]
        ];
    }
}
