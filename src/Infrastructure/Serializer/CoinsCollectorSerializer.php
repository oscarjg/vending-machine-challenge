<?php

namespace App\Infrastructure\Serializer;

use App\Domain\ValueObjects\CoinCollector;
use App\Infrastructure\Helper\PriceFormatHelper;

/**
 * Class MachineResponseSerializer
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Serializer
 */
class CoinsCollectorSerializer
{
    /**
     * @param CoinCollector $coinCollector
     *
     * @return array
     */
    public function __invoke(CoinCollector $coinCollector): array
    {
        $data = [
            'total' => PriceFormatHelper::formatPrice($coinCollector->sum()),
            'coins' => []
        ];

        foreach ($coinCollector->getCoins() as $coin) {
            $data['coins'][] = [
                'value' => PriceFormatHelper::formatPrice($coin->getValue()),
            ];
        }

        return $data;
    }
}
