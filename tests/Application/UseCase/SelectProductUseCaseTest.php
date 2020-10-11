<?php

namespace Tests\Application\UseCase;

use App\Application\UseCase\SelectProductUseCase;
use App\Domain\Exceptions\InvalidInsertedCoinInstanceException;
use App\Domain\Exceptions\InvalidProductSelectionValueException;
use Tests\AbstractTestCase;

/**
 * Class SelectProductUseCaseTest
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package Tests\Application\UseCase
 */
class SelectProductUseCaseTest extends AbstractTestCase
{
    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidProductSelectionValueException
     */
    public function testUseCase()
    {
        $selection = 1;

        $useCase = new SelectProductUseCase(
            $this->machineUuidGenerator()
        );

        $state = $useCase(
            $this->initialState(),
            $selection
        );

        $this->assertEquals($selection, $state->getItemSelector());
    }

    /**
     * @throws InvalidInsertedCoinInstanceException
     * @throws InvalidProductSelectionValueException
     */
    public function testUseCaseThrowsInvalidProductSelection()
    {
        $this->expectException(InvalidProductSelectionValueException::class);

        $selection = 100;

        $useCase = new SelectProductUseCase(
            $this->machineUuidGenerator()
        );

        $useCase(
            $this->initialState(),
            $selection
        );
    }
}
