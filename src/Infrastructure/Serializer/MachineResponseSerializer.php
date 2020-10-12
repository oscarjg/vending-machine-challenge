<?php

namespace App\Infrastructure\Serializer;

use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Model\Product;
use App\Infrastructure\Helper\PriceFormatHelper;

/**
 * Class MachineResponseSerializer
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Serializer
 */
class MachineResponseSerializer
{
    protected CoinsCollectorSerializer $coinsSerializer;

    /**
     * MachineResponseSerializer constructor.
     *
     * @param CoinsCollectorSerializer $coinsSerializer
     */
    public function __construct(CoinsCollectorSerializer $coinsSerializer)
    {
        $this->coinsSerializer = $coinsSerializer;
    }

    /**
     * @param VendingMachineResponse $vendingMachineResponse
     *
     * @return array
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(VendingMachineResponse $vendingMachineResponse): array
    {
        $product = $vendingMachineResponse->getCurrentState()->productSelected();
        $insertedCoins = $vendingMachineResponse->getCurrentState()->getInsertedCoins();
        $amount = (new CoinCollector($insertedCoins))->sum();

        if ($vendingMachineResponse->isValid() === false) {
            return [
                'is-valid' => $vendingMachineResponse->isValid(),
                'errors'   => $vendingMachineResponse->getErrors(),
                'product-selected'  => $product ? $this->product($product) : null,
                'total-amount' => PriceFormatHelper::formatPrice($amount)
            ];
        }

        return [
            'is-valid' => $vendingMachineResponse->isValid(),
            'product-selected'  => $product ? $this->product($product) : null,
            'exchange' => $this->coinsSerializer->__invoke($vendingMachineResponse->getExchange()),
            'total-amount' => PriceFormatHelper::formatPrice($amount)
        ];
    }

    private function product(Product $product): array
    {
        return [
            'name'  => $product->getName(),
            'price' => PriceFormatHelper::formatPrice($product->getPrice()),
        ];
    }
}
