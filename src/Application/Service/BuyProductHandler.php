<?php

namespace App\Application\Service;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\TransactionInterface;
use App\Domain\VendingMachine\Contract\VendingMachineValidatorInterface;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class BuyProductHandler
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Service
 */
class BuyProductHandler implements TransactionInterface
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
     * BuyProductHandler constructor.
     *
     * @param VendingMachineValidatorInterface[] $validators
     * @param ExchangeService $exchangeService
     * @param MachineStateRepository $machineStateRepository
     */
    public function __construct(
        iterable $validators,
        ExchangeService $exchangeService,
        MachineStateRepository $machineStateRepository
    ) {
        $this->validators = $validators;
        $this->exchangeService = $exchangeService;
        $this->machineStateRepository = $machineStateRepository;
    }

    /**
     * @param MachineState $machineState
     *
     * @return VendingMachineResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function run(MachineState $machineState): VendingMachineResponse
    {
        $errors = $this->handleErrors($machineState);

        if (count($errors)) {
            return new VendingMachineResponse(
                false,
                $errors,
                $machineState,
                new CoinCollector([])
            );
        }

        $this
            ->machineStateRepository
            ->saveState($machineState);

        return new VendingMachineResponse(
            true,
            [],
            $machineState,
            $this->exchangeService->__invoke(
                new CoinCollector($machineState->getChange()),
                new CoinCollector($machineState->getInsertedCoins()),
                $machineState->productSelected()
            )
        );
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
