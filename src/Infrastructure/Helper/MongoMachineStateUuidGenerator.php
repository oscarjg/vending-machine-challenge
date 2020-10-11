<?php

namespace App\Infrastructure\Helper;

use App\Domain\VendingMachine\Contract\MachineStateUuidGeneratorInterface;
use Doctrine\ODM\MongoDB\Id\UuidGenerator;

/**
 * Class MongoMachineStateUuidGenerator
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helper
 */
class MongoMachineStateUuidGenerator implements MachineStateUuidGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        $uuid = new UuidGenerator();

        return $uuid->generateV4();
    }
}
