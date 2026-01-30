<?php
declare(strict_types=1);

namespace Holded\Domain\Models;


class Coin
{
    public const VALID_COINS = [
        0.05,
        0.10,
        0.25,
        1.00,
    ];

    private float $value;

    private function __construct(float $value)
    {
        if (!in_array($value, self::VALID_COINS, true)) {
            throw new \InvalidArgumentException('Invalid coin value');
        }

        $this->value = $value;
    }

    public static function create(float $value): Coin
    {
        return new Coin($value);
    }


    public function value(): float
    {
        return $this->value;
    }

    public function isChangeCoin(): bool
    {
        return $this->value !== 1.00;
    }

}