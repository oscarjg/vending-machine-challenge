<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InsertedCoinsException;
use App\Domain\VendingMachine\Model\Coin;

/**
 * Class InsertedCoins
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\ValueObjects
 */
class InsertedCoins
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
     * @throws InsertedCoinsException
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
     * @throws InsertedCoinsException
     */
    private function validate()
    {
        foreach ($this->coins as $coin) {
            if ($coin instanceof Coin) {
                continue;
            }

            throw new InsertedCoinsException("Invalid Coin instance received");
        }
    }
}
