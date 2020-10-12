<?php

namespace Tests\Application\Service;

use App\Application\Service\ExchangeService;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Product;
use Tests\AbstractTestCase;

/**
 * Class ExchangeServiceTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\Service
 */
class ExchangeServiceTest extends AbstractTestCase
{
    /**
     * @param CoinCollector $change
     * @param CoinCollector $amount
     * @param Product $product
     * @param CoinCollector $expected
     *
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     *
     * @dataProvider data
     */
    public function testExchange(
        CoinCollector $change,
        CoinCollector $amount,
        Product $product,
        CoinCollector $expected
    ) {
        $service = new ExchangeService();

        $exchange = $service->__invoke(
            $change,
            $amount,
            $product
        );

        $this->assertEquals($expected->sum(), $exchange->sum());
        $this->assertEquals(count($expected->getCoins()), count($exchange->getCoins()));
    }

    /**
     * @return array[]
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function data(): array
    {
        return [
            [
                new CoinCollector([
                    new Coin(100),
                    new Coin(100),
                    new Coin(100),
                    new Coin(5),
                    new Coin(25),
                ]),
                new CoinCollector([
                    new Coin(100),
                    new Coin(100),
                    new Coin(25),
                ]),
                new Product("p1", 100),
                new CoinCollector([
                    new Coin(100),
                    new Coin(25),
                ]),
            ],
            [
                new CoinCollector([
                    new Coin(100),
                    new Coin(100),
                    new Coin(100),
                    new Coin(5),
                    new Coin(5),
                    new Coin(25),
                ]),
                new CoinCollector([
                    new Coin(100),
                ]),
                new Product("p1", 65),
                new CoinCollector([
                    new Coin(5),
                    new Coin(5),
                    new Coin(25),
                ]),
            ],
            [
                new CoinCollector([
                    new Coin(100),
                    new Coin(100),
                    new Coin(5),
                    new Coin(5),
                    new Coin(5),
                    new Coin(5),
                    new Coin(5),
                    new Coin(10),
                    new Coin(10),
                ]),
                new CoinCollector([
                    new Coin(100),
                ]),
                new Product("p1", 65),
                new CoinCollector([
                    new Coin(5),
                    new Coin(5),
                    new Coin(5),
                    new Coin(10),
                    new Coin(10),
                ]),
            ],
            [
                new CoinCollector([
                    new Coin(100),
                    new Coin(100),
                    new Coin(5),
                    new Coin(5),
                    new Coin(5),
                    new Coin(10),
                    new Coin(10),
                    new Coin(10),
                ]),
                new CoinCollector([
                    new Coin(100),
                ]),
                new Product("p1", 65),
                new CoinCollector([
                    new Coin(5),
                    new Coin(10),
                    new Coin(10),
                    new Coin(10),
                ]),
            ],

        ];
    }
}
