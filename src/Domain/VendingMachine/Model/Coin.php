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
     * @var float
     */
    protected float $value;

    /**
     * Coin constructor.
     *
     * @param string $id
     * @param float $value
     */
    public function __construct(string $id, float $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    public function __serialize(): array
    {
        return [
            "value" => $this->value
        ];
    }

}
