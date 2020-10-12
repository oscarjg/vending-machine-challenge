<?php

namespace App\Application\Handler;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\Product;
use App\Infrastructure\Helper\PriceFormatHelper;

/**
 * Class CurrentMachineState
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\Service
 */
class PopulateMachineHandler
{
    /**
     * @var MachineStateRepository
     */
    protected MachineStateRepository $repository;

    /**
     * @var MachineStateUuidGeneratorInterface
     */
    protected MachineStateUuidGeneratorInterface $uuidGenerator;

    /**
     * CurrentMachineState constructor.
     *
     * @param MachineStateRepository $repository
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     */
    public function __construct(MachineStateRepository $repository, MachineStateUuidGeneratorInterface $uuidGenerator)
    {
        $this->repository = $repository;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param array $products
     * @param array $change
     *
     * @return VendingMachineResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(array $products, array $change): VendingMachineResponse
    {
        $machineState = null;

        try {
            $machineState = MachineStateFactory::createMachineState(
                $this->uuidGenerator->generate(),
                new CoinCollector([]),
                $this->inventory($products),
                $this->change($change),
                null
            );

            $this->repository->saveState($machineState);

            return new VendingMachineResponse(true, [], $machineState, new CoinCollector([]));
        } catch (\Throwable $e) {
            return new VendingMachineResponse(false, [$e->getMessage()], $machineState, new CoinCollector([]));
        }
    }

    /**
     * @param array $products
     *
     * @return Inventory
     */
    protected function inventory(array $products): Inventory
    {
        $inventory = [];

        foreach ($products as $productData) {
            if (
                isset($productData['name']) &&
                isset($productData['price']) &&
                isset($productData['quantity']) &&
                isset($productData['selector'])
            ) {
                $inventory [] = new Item(
                    new Product($productData['name'], PriceFormatHelper::normalizePrice($productData['price'])),
                    $productData['quantity'],
                    $productData['selector']
                ) ;
            }
        }
        return new Inventory($inventory);
    }

    /**
     * @param array $change
     *
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    protected function change(array $change)
    {
        $coins = [];

        foreach ($change as $changeData) {
            foreach ($changeData as $value => $iteration) {
                for ($x = 0; $x < $iteration; $x++) {
                    $coins[] = new Coin(PriceFormatHelper::normalizePrice($value));
                }
            }
        }

        return new CoinCollector($coins);
    }
}
