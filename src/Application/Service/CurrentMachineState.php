<?php

namespace App\Application\Service;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class CurrentMachineState
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Service
 */
class CurrentMachineState
{
    /**
     * @var MachineStateRepository
     */
    protected MachineStateRepository $repository;

    /**
     * @var MachineStateUuidGeneratorInterface
     */
    protected MachineStateUuidGeneratorInterface $uuidGenerator;

    /**
     * CurrentMachineState constructor.
     *
     * @param MachineStateRepository $repository
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     */
    public function __construct(
        MachineStateRepository $repository,
        MachineStateUuidGeneratorInterface $uuidGenerator
    ) {
        $this->repository = $repository;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(): MachineState
    {
        $machineState = $this->repository->fetchCurrentState();

        if ($machineState === null) {
            $machineState = MachineStateFactory::createMachineState(
                $this->uuidGenerator->generate(),
                new CoinCollector([]),
                new Inventory([]),
                new CoinCollector([]),
                null
            );

            $this->repository->saveState($machineState);

            return $machineState;
        }

        return $machineState;
    }
}
