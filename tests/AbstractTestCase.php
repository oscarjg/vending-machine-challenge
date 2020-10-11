<?php

namespace Tests;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\MachineState;
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
            new InsertedCoins([])
        );
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
