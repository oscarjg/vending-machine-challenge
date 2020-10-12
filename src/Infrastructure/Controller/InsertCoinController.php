<?php

namespace App\Infrastructure\Controller;

use App\Application\Handler\InsertCoinHandler;
use App\Application\Service\CurrentMachineState;
use App\Application\UseCase\InsertMoneyUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\CoinCollector;
use App\Domain\ValueObjects\Inventory;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Infrastructure\Helper\PriceFormatHelper;
use App\Infrastructure\Helper\RequestParamHelper;
use App\Infrastructure\Serializer\CoinsCollectorSerializer;;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyProductController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/insert-coin", methods={"POST"})
 */
class InsertCoinController
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
     * @var CoinsCollectorSerializer
     */
    protected CoinsCollectorSerializer $serializer;

    /**
     * BuyProductController constructor.
     *
     * @param MachineStateRepository $repository
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     * @param CoinsCollectorSerializer $serializer
     */
    public function __construct(
        MachineStateRepository $repository,
        MachineStateUuidGeneratorInterface $uuidGenerator,
        CoinsCollectorSerializer $serializer
    ) {
        $this->repository = $repository;
        $this->uuidGenerator = $uuidGenerator;
        $this->serializer = $serializer;
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
        $coinValue = RequestParamHelper::param($request, 'coin', 0);

        $handler = new InsertCoinHandler(
            new InsertMoneyUseCase(
                $this->uuidGenerator
            ),
            $this->repository,
            new CurrentMachineState(
                $this->repository,
                $this->uuidGenerator
            )
        );

        return $this->handleResponse($handler(PriceFormatHelper::normalizePrice($coinValue)));
    }

    /**
     * @param VendingMachineResponse $response
     *
     * @return JsonResponse
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidInsertedCoinValueException
     */
    private function handleResponse(VendingMachineResponse $response): JsonResponse
    {
        if ($response->isValid()) {
            return JsonResponse::create([
                "message" => "Coin inserted successfully",
                "data"    => [
                    "coins-inserted" => $this
                        ->serializer
                        ->__invoke(new CoinCollector($response->getCurrentState()->getInsertedCoins())),
                ]
            ]);
        }

        return JsonResponse::create([
            "message" => "Ops! Some errors has been occurred",
            "errors" => $response->getErrors()
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
