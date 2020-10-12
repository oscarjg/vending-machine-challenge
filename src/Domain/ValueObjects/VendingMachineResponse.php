<?php

namespace App\Domain\ValueObjects;

use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class VendingMachineResponse
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\ValueObjects
 */
class VendingMachineResponse
{
    protected bool $isValid;
    protected array $errors;
    protected MachineState $currentState;
    protected CoinCollector $exchange;

    /**
     * VendingMachineResponse constructor.
     *
     * @param bool $isValid
     * @param array $errors
     * @param MachineState $currentState
     * @param CoinCollector $exchange
     */
    public function __construct(bool $isValid, array $errors, MachineState $currentState, CoinCollector $exchange)
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
        $this->currentState = $currentState;
        $this->exchange = $exchange;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return MachineState
     */
    public function getCurrentState(): MachineState
    {
        return $this->currentState;
    }

    /**
     * @return CoinCollector
     */
    public function getExchange(): CoinCollector
    {
        return $this->exchange;
    }
}
