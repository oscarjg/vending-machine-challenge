<?php

namespace App\Application\UseCase;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class ReturnMoneyUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\UseCase
 */
class ReturnMoneyUseCase
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
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(MachineState $machineState): MachineState
    {
        return MachineStateFactory::createMachineState(
            $this->uuidGenerator->generate(),
            new CoinCollector([]),
            new Inventory($machineState->getItems()),
            new CoinCollector($machineState->getChange()),
            $machineState->getItemSelector()
        );
    }
}
