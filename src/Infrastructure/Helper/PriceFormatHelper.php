<?php

namespace App\Infrastructure\Helper;

/**
 * Class PriceFormatHelper
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helper
 */
class PriceFormatHelper
{
    /**
     * @param int $price
     *
     * @return string
     */
    public static function formatPrice(int $price): string
    {
        return number_format($price / 100, 2);
    }

    /**
     * @param string $price
     *
     * @return int
     */
    public static function normalizePrice(string $price): int
    {
        return $price * 100;
    }
}
