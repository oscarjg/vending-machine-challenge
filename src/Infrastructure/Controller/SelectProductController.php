<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CurrentMachineState;
use App\Application\Handler\SelectProductHandler;
use App\Application\UseCase\SelectProductUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidInsertedCoinValueException;
use App\Domain\ValueObjects\VendingMachineResponse;
use App\Domain\VendingMachine\Contract\MachineStateRepository;
use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use App\Infrastructure\Helper\RequestParamHelper;
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
 * @Route(path="/select-product", methods={"POST"})
 */
class SelectProductController
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
        $selection = RequestParamHelper::param($request, 'selector', 0);

        $handler = new SelectProductHandler(
            new SelectProductUseCase(
                $this->uuidGenerator
            ),
            $this->repository,
            new CurrentMachineState(
                $this->repository,
                $this->uuidGenerator
            )
        );

        $response = $handler($selection);

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
            "Your product has been selected successfully" :
            "Ops! Some errors has been occurred";
    }
}
