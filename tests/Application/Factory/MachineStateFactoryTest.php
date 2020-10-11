<?php

namespace Tests\Application\Factory;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\InsertedCoins;
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
     * @param InsertedCoins $insertedCoins
     * @param Inventory $inventory
     * @param int|null $itemSelected
     *
     * @dataProvider data
     */
    public function testCreateMachineStateInstance(
        string $uuid,
        InsertedCoins $insertedCoins,
        Inventory $inventory,
        ?int $itemSelected
    ) {
        $machineState = MachineStateFactory::createMachineState(
            $uuid,
            $insertedCoins,
            $inventory,
            $itemSelected
        );

        $this->assertInstanceOf(MachineState::class, $machineState);
        $this->assertEquals($uuid, $machineState->getUuid());
        $this->assertEquals($insertedCoins->getCoins(), $machineState->getInsertedCoins());
        $this->assertEquals($inventory->getItems(), $machineState->getItems());
        $this->assertEquals($itemSelected, $machineState->getItemSelected());
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testInvalidCoinsInserted()
    {
        $this->expectException(InvalidInsertedCoinInstanceException::class);

        MachineStateFactory::createMachineState(
            "some-uuid",
            new InsertedCoins([
                new \stdClass(),
                new \stdClass(),
            ]),
            $this->emptyInventory(),
            $this->defaultItemSelected()
        );
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testInvalidCoinsValuesInserted()
    {
        $this->expectException(InvalidInsertedCoinValueException::class);

        MachineStateFactory::createMachineState(
            "some-uuid",
            new InsertedCoins([
                new Coin(1.00),
                new Coin(1.50),
            ]),
            $this->emptyInventory(),
            $this->defaultItemSelected()
        );
    }

    /**
     * @return array[]
     * @throws InvalidInsertedCoinInstanceException
     */
    public function data(): array
    {
        return [
            [
                "foo-id",
                $this->emptyInsertedCoins(),
                $this->emptyInventory(),
                $this->defaultItemSelected()
            ],
            [
                "foo-id",
                new InsertedCoins([
                    new Coin(1.00),
                    new Coin(1.00),
                    new Coin(0.25),
                ]),
                $this->defaultInventory(),
                $this->defaultItemSelected()
            ]
        ];
    }
}
