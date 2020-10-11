<?php

namespace App\Application\UseCase;

use App\Application\Factory\MachineStateFactory;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Domain\VendingMachine\Model\Coin;
use App\Domain\VendingMachine\Model\MachineState;

/**
 * Class InsertMoneyUseCase
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Application\UseCase
 */
class InsertMoneyUseCase
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
     * @param float $coinValue
     *
     * @return MachineState
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(
        MachineState $machineState,
        float $coinValue
    ): MachineState {
        $currentInsertedMoney = $machineState->getInsertedCoins();

        $currentInsertedMoney[] = new Coin($coinValue);

        return MachineStateFactory::createMachineState(
            $this->uuidGenerator->generate(),
            new CoinCollector($currentInsertedMoney),
            new Inventory($machineState->getItems()),
            new CoinCollector($machineState->getChange()),
            $machineState->getItemSelected()
        );
    }
}
