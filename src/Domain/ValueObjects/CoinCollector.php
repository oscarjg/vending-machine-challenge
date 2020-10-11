<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class CoinCollector
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\ValueObjects
 */
class CoinCollector
{
    /**
     * @var iterable
     */
    protected iterable $coins;

    /**
     * InsertedCoins constructor.
     *
     * @param iterable $coins
     *
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __construct(iterable $coins)
    {
        $this->coins = $coins;

        $this->validate();
    }

    /**
     * @return iterable
     */
    public function getCoins(): iterable
    {
        return $this->coins;
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function validate(): void
    {
        foreach ($this->coins as $coin) {
            if (!$coin instanceof Coin) {
                throw new InvalidInsertedCoinInstanceException("Invalid Coin instance received");
            }

            if (!in_array($coin->getValue(), MachineState::ACCEPTED_COINS)) {
                throw new InvalidInsertedCoinValueException(
                    sprintf(
                        'Invalid Coin value. Only %s are accepted, %s received',
                        implode(",", MachineState::ACCEPTED_COINS),
                        $coin->getValue()
                    )
                );
            }
        }
    }
}
