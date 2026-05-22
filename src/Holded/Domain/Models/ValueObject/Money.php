<?php

declare(strict_types=1);

namespace Holded\Domain\Models\ValueObject;

use InvalidArgumentException;

final class Money
{
    private const ALLOWED_COINS = [0.05, 0.10, 0.20, 0.50, 1.00, 2.00];
    private float $amount;

    private function __construct(float $amount)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Money is negative.");
        }

        $this->amount = round($amount, 2);
    }

    public static function fromCoin(float $coin): self
    {
        if (!in_array(round($coin, 2), self::ALLOWED_COINS, true)) {
            throw new InvalidArgumentException("This coin {$coin} non accepted.");
        }

        return new self($coin);
    }

    public static function fromPriceProduct(float $prices): self
    {
        return new self($prices);
    }

    public static function zero(): self
    {
        return new self(0.0);
    }

    public function add(self $other): self
    {
        return new self($this->amount + $other->amount());
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function subtract(self $other): self
    {
        return new self($this->amount - $other->amount());
    }

    public function isLessThan(self $other): bool
    {
        return $this->amount < $other->amount();
    }
}