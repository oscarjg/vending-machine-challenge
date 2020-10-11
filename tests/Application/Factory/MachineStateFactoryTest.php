<?php

namespace Tests\Application\Factory;

use App\Application\Factory\MachineStateFactory;
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
     * @param iterable $insertedCoins
     *
     * @dataProvider data
     */
    public function testCreateMachineStateInstance(
        string $uuid,
        iterable $insertedCoins
    ) {
        $machineState = MachineStateFactory::createMachineState(
            $uuid,
            $insertedCoins
        );

        $this->assertInstanceOf(MachineState::class, $machineState);
        $this->assertEquals($uuid, $machineState->getUuid());
        $this->assertEquals($insertedCoins, $machineState->getInsertedCoins());
    }

    /**
     * @return array[]
     */
    public function data(): array
    {
        return [
            [
                "foo-id",
                []
            ]
        ];
    }
}
