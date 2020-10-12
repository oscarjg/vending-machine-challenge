<?php

namespace App\Infrastructure\Controller;

use App\Application\Handler\ReturnMoneyHandler;
use App\Application\Service\CurrentMachineState;
use App\Application\UseCase\ReturnMoneyUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Infrastructure\Serializer\MachineResponseSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReturnMoneyController
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Controller
 *
 * @Route(path="/return-money", methods={"POST"})
 */
class ReturnMoneyController
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
        $handler = new ReturnMoneyHandler(
            new ReturnMoneyUseCase(
                $this->uuidGenerator
            ),
            $this->repository,
            new CurrentMachineState(
                $this->repository,
                $this->uuidGenerator
            )
        );

        $response = $handler();

        return JsonResponse::create([
            "message" => $this->message($response),
            "data"    => $this->serializer->__invoke($response)
        ], $response->isValid() ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param VendingMachineResponse $response
     *
     * @return string
     */
    private function message(VendingMachineResponse $response): string
    {
        return $response->isValid() ?
            "Your money is back!" :
            "Ops! Some errors has been occurred";
    }
}
