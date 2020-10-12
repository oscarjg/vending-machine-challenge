<?php

namespace App\Domain\VendingMachine\Model;

/**
 * Class Coin
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Domain\VendingMachine\Model
 */
class Coin
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var int
     */
    protected int $value;

    /**
     * Coin constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
