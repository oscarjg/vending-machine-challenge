<?php

namespace Tests\Application\Validator;

use App\Application\Validator\HasProductStockValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\MachineState;
use App\Domain\VendingMachine\Model\Product;
use Tests\AbstractTestCase;

/**
 * Class HasProductStockValidatorTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Validator
 */
class HasProductStockValidatorTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     */
    public function testIsValid()
    {
        $validator = new HasProductStockValidator();

        foreach ([1,2,3] as $itemSelected) {
            $isValid = $validator->isValid(
                $this->machineState($itemSelected, $this->defaultInventory())
            );

            $this->assertTrue($isValid);
        }
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function testIsNotValid()
    {
        $validator = new HasProductStockValidator();

        foreach ([1,2,3] as $itemSelected) {
            $isValid = $validator->isValid(
                $this->machineState($itemSelected, $this->inventoryWithoutStock())
            );

            $this->assertFalse($isValid);
        }
    }

    /**
     * @param int $itemSelected
     * @param Inventory $inventory
     *
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function machineState(int $itemSelected, Inventory $inventory): MachineState
    {
        return new MachineState(
            "uuid",
            new CoinCollector([]),
            $inventory,
            $this->defaultChange(),
            $itemSelected
        );
    }

    /**
     * @return Inventory
     */
    private function inventoryWithoutStock(): Inventory
    {
        return new Inventory([
            new Item(new Product("p1", 1.00), 0, 1),
            new Item(new Product("p2", 0.65), 0, 2),
            new Item(new Product("p3", 1.50), 0, 3),
        ]);
    }
}
