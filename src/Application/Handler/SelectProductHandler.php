<?php

namespace App\Application\Handler;

use App\Application\Service\CurrentMachineState;
use App\Application\UseCase\SelectProductUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;

/**
 * Class SelectProductHandler
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Service
 */
class SelectProductHandler
{
    protected SelectProductUseCase $useCase;

    protected MachineStateRepository $repository;

    protected CurrentMachineState $currentStateService;

    /**
     * SelectProductHandler constructor.
     *
     * @param SelectProductUseCase $useCase
     * @param MachineStateRepository $repository
     * @param CurrentMachineState $currentStateService
     */
    public function __construct(
        SelectProductUseCase $useCase,
        MachineStateRepository $repository,
        CurrentMachineState $currentStateService
    ) {
        $this->useCase = $useCase;
        $this->repository = $repository;
        $this->currentStateService = $currentStateService;
    }

    /**
     * @param int $itemSelector
     *
     * @return VendingMachineResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(int $itemSelector): VendingMachineResponse
    {
        $machineState = $this->currentStateService->__invoke();

        try {
            $machineState = $this
                ->useCase
                ->__invoke($machineState, $itemSelector);

            $this->repository->saveState($machineState);

            return new VendingMachineResponse(true, [], $machineState, new CoinCollector([]));
        } catch (\Throwable $e) {
            return new VendingMachineResponse(false, [$e->getMessage()], $machineState, new CoinCollector([]));
        }
    }
}
