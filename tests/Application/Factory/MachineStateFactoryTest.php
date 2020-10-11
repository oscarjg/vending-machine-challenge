<?php

namespace Tests\Application\Factory;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InsertedCoinsException;
use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\MachineState;
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
     *
     * @dataProvider data
     */
    public function testCreateMachineStateInstance(
        string $uuid,
        InsertedCoins $insertedCoins
    ) {
        $machineState = MachineStateFactory::createMachineState(
            $uuid,
            $insertedCoins
        );

        $this->assertInstanceOf(MachineState::class, $machineState);
        $this->assertEquals($uuid, $machineState->getUuid());
        $this->assertEquals($insertedCoins->getCoins(), $machineState->getInsertedCoins());
        $this->assertCount(count($insertedCoins->getCoins()), $machineState->getInsertedCoins());
    }

    public function testInvalidCoinsInserted()
    {
        $this->expectException(InsertedCoinsException::class);

        $machineState = MachineStateFactory::createMachineState(
            "some-uuid",
            new InsertedCoins([
                new \stdClass(),
                new \stdClass(),
            ])
        );
    }

    /**
     * @return array[]
     * @throws InsertedCoinsException
     */
    public function data(): array
    {
        return [
            [
                "foo-id",
                new InsertedCoins([])
            ],
            [
                "foo-id",
                new InsertedCoins([
                    new Coin(1.00),
                    new Coin(1.00),
                    new Coin(0.25),
                ])
            ]
        ];
    }
}
