<?php

namespace App\Application\Service;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Product;

/**
 * Class ExchangeService
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Service
 */
class ExchangeService
{
    /**
     * @var CoinCollector
     */
    private CoinCollector $exchange;

    /**
     * ExchangeService constructor.
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __construct()
    {
        $this->exchange = new CoinCollector([]);
    }

    /**
     * @param CoinCollector $exchangeCoins
     * @param CoinCollector $amountCoins
     * @param Product $productSold
     *
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(
        CoinCollector $exchangeCoins,
        CoinCollector $amountCoins,
        Product $productSold
    ): CoinCollector {
        $exchange = $amountCoins->sum() - $productSold->getPrice();

        $this->calculate(
            $exchange,
            $exchange,
            $this->prepareExchangeMap($exchangeCoins)
        );

        return $this->exchange;
    }

    /**
     * @param int $exchange
     * @param int $baseExchange
     * @param array $exchangeMap
     *
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function calculate(
        int $exchange,
        int $baseExchange,
        array $exchangeMap
    ) {
        if ($exchange === 0) {
            return;
        }

        if (isset($exchangeMap[$exchange]) && $exchangeMap[$exchange] > 0) {
            $this->exchange = new CoinCollector(array_merge(
                $this->exchange->getCoins(),
                [new Coin($exchange)]
            ));

            $exchangeMap[$exchange] = $exchangeMap[$exchange] - 1;
            $nextExchange = ($baseExchange - $exchange);
            $this->calculate($nextExchange, $nextExchange, $exchangeMap);
        } else {
            $this->calculate(($exchange - 5), $baseExchange, $exchangeMap);
        }
    }

    /**
     * @param CoinCollector $exchangeCoins
     *
     * @return array
     */
    private function prepareExchangeMap(CoinCollector $exchangeCoins): array
    {
        $exchangeMap = [];

        foreach ($exchangeCoins->getCoins() as $exchangeCoin) {
            if (isset($exchangeMap[$exchangeCoin->getValue()])) {
                $exchangeMap[$exchangeCoin->getValue()] = $exchangeMap[$exchangeCoin->getValue()] + 1;
            } else {
                $exchangeMap[$exchangeCoin->getValue()] = 1;
            }
        }

        return $exchangeMap;
    }
}
