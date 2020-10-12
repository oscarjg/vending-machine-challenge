<?php

namespace App\Infrastructure\Controller;

use App\Application\Handler\BuyProductHandler;
use App\Application\Service\CurrentMachineState;
use App\Application\Service\ExchangeService;
use App\Application\UseCase\BuyProductUseCase;
use App\Application\Validator\HasEnoughAmountValidator;
use App\Application\Validator\HasEnoughChangeValidator;
use App\Application\Validator\HasProductSelectedValidator;
use App\Application\Validator\HasProductStockValidator;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Infrastructure\Serializer\MachineResponseSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyProductController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/buy", methods={"POST"})
 */
class BuyProductController
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
     * @var MachineResponseSerializer
     */
    protected MachineResponseSerializer $serializer;

    /**
     * BuyProductController constructor.
     *
     * @param MachineStateRepository $repository
     * @param MachineStateUuidGeneratorInterface $uuidGenerator
     * @param MachineResponseSerializer $serializer
     */
    public function __construct(
        MachineStateRepository $repository,
        MachineStateUuidGeneratorInterface $uuidGenerator,
        MachineResponseSerializer $serializer
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
        $response = (new BuyProductHandler(
            new BuyProductUseCase(
                $this->uuidGenerator
            ),
            new CurrentMachineState(
                $this->repository,
                $this->uuidGenerator
            ),
            $this->invalidators(),
            new ExchangeService(),
            $this->repository
        ))();

        return JsonResponse::create([
            "message" => $response->isValid() ?
                "Yeeeah! Thanks for your purchase. Enjoy your product" :
                "Opps! Something was wrong",
            "data" => $this->serializer->__invoke($response)
        ], $response->isValid() ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @return array
     */
    private function invalidators(): array
    {
        return [
            new HasProductSelectedValidator(),
            new HasProductStockValidator(),
            new HasEnoughAmountValidator(),
            new HasEnoughChangeValidator(),
        ];
    }
}
