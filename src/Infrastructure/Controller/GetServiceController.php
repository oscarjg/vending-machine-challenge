<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CurrentMachineState;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Infrastructure\Serializer\CoinsCollectorSerializer;
use App\Infrastructure\Serializer\InventorySerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetServiceController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/service", methods={"GET"})
 */
class GetServiceController
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
     * @var InventorySerializer
     */
    protected InventorySerializer $inventorySerializer;

    /**
     * @var CoinsCollectorSerializer
     */
    protected CoinsCollectorSerializer $coinsCollectorSerializer;

    /**
     * PopulateInventoryController constructor.
     *
     * @param MachineStateRepository $repository
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     * @param InventorySerializer $serializer
     * @param CoinsCollectorSerializer $coinsCollectorSerializer
     */
    public function __construct(
        MachineStateRepository $repository,
        MachineStateUuidGeneratorInterface $uuidGenerator,
        InventorySerializer $serializer,
        CoinsCollectorSerializer $coinsCollectorSerializer
    ) {
        $this->repository = $repository;
        $this->uuidGenerator = $uuidGenerator;
        $this->inventorySerializer = $serializer;
        $this->coinsCollectorSerializer = $coinsCollectorSerializer;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    public function __invoke(Request $request)
    {
        $currentState = (new CurrentMachineState(
            $this->repository,
            $this->uuidGenerator
        ))();

        return JsonResponse::create([
            "data" => [
               "inventory" => $this
                   ->inventorySerializer
                   ->__invoke(new Inventory($currentState->getItems())),
               "change-available" => $this
                   ->coinsCollectorSerializer->
                   __invoke(new CoinCollector($currentState->getChange())),
            ]
        ]);
    }
}
