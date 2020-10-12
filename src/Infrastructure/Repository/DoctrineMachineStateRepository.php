<?php

namespace App\Infrastructure\Repository;

use App\Domain\VendingMachine\Model\MachineState;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use App\Domain\VendingMachine\Contract\MachineStateRepository as ModelRepository;

/**
 * Class CarRepository
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Doctrine
 */
class DoctrineMachineStateRepository extends ServiceDocumentRepository implements ModelRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, MachineState::class);
    }

    /**
     * @param MachineState $machineState
     *
     * @return MachineState
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function saveState(MachineState $machineState): MachineState
    {
        $this
            ->getDocumentManager()
            ->persist($machineState);

        $this
            ->getDocumentManager()
            ->flush();

        return $machineState;
    }

    /**
     * @return MachineState|null
     */
    public function fetchCurrentState(): ?MachineState
    {
        return $this
            ->createQueryBuilder()
            ->sort('id', 'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
    }
}
