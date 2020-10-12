<?php

namespace App\Infrastructure\Serializer;

use App\Domain\ValueObjects\Inventory;
use App\Infrastructure\Helper\PriceFormatHelper;

/**
 * Class MachineResponseSerializer
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Serializer
 */
class InventorySerializer
{
    /**
     * @param Inventory $inventory
     *
     * @return array
     */
    public function __invoke(Inventory $inventory): array
    {
        $data = [];

        foreach ($inventory->getItems() as $item) {
            $data[] = [
                'quantity' => $item->getQuantity(),
                'selector' => $item->getSelector(),
                'product' => [
                    'name'  => $item->getProduct()->getName(),
                    'price' => PriceFormatHelper::formatPrice($item->getProduct()->getPrice()),
                ]
            ];
        }

        return $data;
    }
}
