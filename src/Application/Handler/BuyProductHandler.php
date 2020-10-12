<?php

namespace App\Application\Handler;

use App\Application\Service\CurrentMachineState;
use App\Application\Service\ExchangeService;
use App\Application\UseCase\BuyProductUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\VendingMachineValidatorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class BuyProductHandler
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Service
 */
class BuyProductHandler
{
    /**
     * @var VendingMachineValidatorInterface[]
     */
    protected iterable $validators;

    /**
     * @var ExchangeService
     */
    protected ExchangeService $exchangeService;

    /**
     * @var MachineStateRepository
     */
    protected MachineStateRepository $machineStateRepository;

    /**
     * @var CurrentMachineState
     */
    protected CurrentMachineState $currentStateService;

    /**
     * @var BuyProductUseCase
     */
    protected BuyProductUseCase $useCase;

    /**
     * BuyProductHandler constructor.
     *
     * @param BuyProductUseCase $useCase
     * @param CurrentMachineState $currentStateService
     * @param iterable $validators
     * @param ExchangeService $exchangeService
     * @param MachineStateRepository $machineStateRepository
     */
    public function __construct(
        BuyProductUseCase $useCase,
        CurrentMachineState $currentStateService,
        iterable $validators,
        ExchangeService $exchangeService,
        MachineStateRepository $machineStateRepository
    ) {
        $this->useCase = $useCase;
        $this->currentStateService = $currentStateService;
        $this->validators = $validators;
        $this->exchangeService = $exchangeService;
        $this->machineStateRepository = $machineStateRepository;
    }

    /**
     * @return VendingMachineResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(): VendingMachineResponse
    {
        $machineState = $this->currentStateService->__invoke();

        $errors = $this->handleErrors($machineState);

        if (count($errors)) {
            return new VendingMachineResponse(
                false,
                $errors,
                $machineState,
                new CoinCollector([])
            );
        }

        $exchange = $this->exchangeService->__invoke(
            new CoinCollector($machineState->getChange()),
            new CoinCollector($machineState->getInsertedCoins()),
            $machineState->productSelected()
        );

        $response = new VendingMachineResponse(
            true,
            [],
            $machineState,
            $exchange
        );

        $machineState = $this->useCase->__invoke(
            $machineState,
            $exchange
        );

        $this
            ->machineStateRepository
            ->saveState($machineState);

        return $response;
    }

    /**
     * @param MachineState $machineState
     *
     * @return array
     */
    private function handleErrors(MachineState $machineState): array
    {
        $errors = [];

        foreach ($this->validators as $validator) {
            if ($validator->isValid($machineState) === false) {
                $errors[] = $validator->message();
            }
        }

        return $errors;
    }
}
