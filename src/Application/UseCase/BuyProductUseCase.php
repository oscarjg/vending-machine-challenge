<?php

namespace App\Application\UseCase;

use App\Application\Factory\MachineStateFactory;
use App\Application\Service\ExchangeService;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\Item;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class BuyProductUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\UseCase
 */
class BuyProductUseCase
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
    public function __construct(
        MachineStateUuidGeneratorInterface $uuidGenerator
    ) {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param MachineState $machineState
     * @param CoinCollector $exchange
     *
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(
        MachineState $machineState,
        CoinCollector $exchange
    ): MachineState {
        return MachineStateFactory::createMachineState(
            $this->uuidGenerator->generate(),
            new CoinCollector([]),
            $this->reduceInventory($machineState),
            $this->upgradeChangeBalance($machineState, $exchange),
            null
        );
    }

    /**
     * @param MachineState $machineState
     * @param CoinCollector $exchange
     *
     * @return CoinCollector
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function upgradeChangeBalance(MachineState $machineState, CoinCollector $exchange): CoinCollector
    {
        $currentChange = $machineState->getChange();
        $currentInsertedCoins = $machineState->getInsertedCoins();

        $balance = array_map(function (Coin $coin) {
            return $coin->getValue();
        }, array_merge($currentChange, $currentInsertedCoins));

        foreach ($exchange->getCoins() as $exchangeCoin) {
            $key = array_search($exchangeCoin->getValue(), $balance);

            if ($key !== false) {
                unset($balance[$key]);
            }
        }

        $coins = [];

        foreach ($balance as $value) {
            $coins[] = new Coin($value);
        }

        return new CoinCollector($coins);
    }

    /**
     * @param MachineState $machineState
     *
     * @return Inventory
     */
    private function reduceInventory(MachineState $machineState): Inventory
    {
        $items = array_map(function (Item $item) use ($machineState) {
            if ($item->getSelector() === $machineState->getItemSelector()) {
                return new Item(
                    $item->getProduct(),
                    $item->getQuantity() - 1,
                    $item->getSelector()
                );
            } else {
                return $item;
            }
        }, $machineState->getItems());

        return new Inventory($items);
    }
}
