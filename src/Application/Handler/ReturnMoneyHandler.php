<?php

namespace App\Application\Handler;

use App\Application\Service\CurrentMachineState;
use App\Application\UseCase\ReturnMoneyUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;

/**
 * Class RefundMoneyHandler
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Handler
 */
class ReturnMoneyHandler
{
    protected ReturnMoneyUseCase $useCase;

    protected MachineStateRepository $repository;

    protected CurrentMachineState $currentStateService;

    /**
     * RefundMoneyHandler constructor.
     *
     * @param ReturnMoneyUseCase $useCase
     * @param MachineStateRepository $repository
     * @param CurrentMachineState $currentStateService
     */
    public function __construct(
        ReturnMoneyUseCase $useCase,
        MachineStateRepository $repository,
        CurrentMachineState $currentStateService
    ) {
        $this->useCase = $useCase;
        $this->repository = $repository;
        $this->currentStateService = $currentStateService;
    }

    /**
     * @return VendingMachineResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(): VendingMachineResponse
    {
        $machineState = $this->currentStateService->__invoke();

        try {
            $machineState = $this
                ->useCase
                ->__invoke($machineState);

            $this->repository->saveState($machineState);

            return new VendingMachineResponse(true, [], $machineState, new CoinCollector([]));
        } catch (\Throwable $e) {
            return new VendingMachineResponse(false, [$e->getMessage()], $machineState, new CoinCollector([]));
        }
    }
}
