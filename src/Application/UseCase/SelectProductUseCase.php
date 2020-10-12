<?php

namespace App\Application\UseCase;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\Exceptions\InvalidProductSelectionValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class SelectProductUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\UseCase
 */
class SelectProductUseCase
{
    /**
     * @var MachineStateUuidGeneratorInterface
     */
    protected MachineStateUuidGeneratorInterface $uuidGenerator;

    /**
     * InsertMoneyUseCase constructor.
     *
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     */
    public function __construct(MachineStateUuidGeneratorInterface $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param MachineState $machineState
     * @param int $productSelection
     *
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidProductSelectionValueException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(MachineState $machineState, int $productSelection): MachineState
    {
        $this->validate($machineState->getItems(), $productSelection);

        return MachineStateFactory::createMachineState(
            $this->uuidGenerator->generate(),
            new CoinCollector($machineState->getInsertedCoins()),
            new Inventory($machineState->getItems()),
            new CoinCollector($machineState->getChange()),
            $productSelection
        );
    }

    /**
     * @param Item[] $items
     * @param int $productSelection
     *
     * @throws InvalidProductSelectionValueException
     */
    private function validate(iterable $items, int $productSelection): void
    {
        $selections = array_map(function (Item $item) {
            return $item->getSelector();
        }, $items);

        if (!in_array($productSelection, $selections)) {
            throw new InvalidProductSelectionValueException(
                "There are not any product under $productSelection selection number"
            );
        }
    }
}
