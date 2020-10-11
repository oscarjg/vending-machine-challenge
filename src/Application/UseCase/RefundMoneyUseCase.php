<?php

namespace App\Application\UseCase;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\ValueObjects\InsertedCoins;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class InsertMoneyUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\UseCase
 */
class RefundMoneyUseCase
{
    /**
     * @var MachineStateUuidGeneratorInterface
     */
    protected MachineStateUuidGeneratorInterface $uuidGenerator;

    /**
     * InsertMoneyUseCase constructor.
     *
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     */
    public function __construct(MachineStateUuidGeneratorInterface $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param MachineState $machineState
     *
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     */
    public function __invoke(MachineState $machineState): MachineState
    {
        return MachineStateFactory::createMachineState(
            $this->uuidGenerator->generate(),
            new InsertedCoins([]),
            new Inventory($machineState->getItems()),
            $machineState->getItemSelected()
        );
    }
}
