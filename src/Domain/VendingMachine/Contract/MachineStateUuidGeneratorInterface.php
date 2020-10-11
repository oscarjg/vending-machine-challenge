<?php

namespace App\Domain\VendingMachine\Contract;

/**
 * Interface MachineStateUuidGeneratorInterface
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Contract
 */
interface MachineStateUuidGeneratorInterface
{
    public function generate(): string;
}
