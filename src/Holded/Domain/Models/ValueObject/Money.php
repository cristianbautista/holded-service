<?php

declare(strict_types=1);

namespace Holded\Domain\Models\ValueObject;

use InvalidArgumentException;

final class Money
{
    private const ALLOWED_COINS = [5, 10, 20, 50, 100, 200];
    private int $amount;

    private function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Money is negative.");
        }
        $this->amount = $amount;
    }

    public static function fromCoin(float $coin): self
    {
        $cents = (int)round($coin * 100);
        if (!in_array($cents, self::ALLOWED_COINS, true)) {
            throw new InvalidArgumentException("This coin {$coin} non accepted.");
        }
        return new self($cents);
    }

    public static function fromPriceProduct(float $prices): self
    {
        return new self((int)round($prices * 100));
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function add(self $other): self
    {
        return new self($this->amount + $other->amount);
    }

    public function subtract(self $other): self
    {
        return new self($this->amount - $other->amount);
    }

    public function isLessThan(self $other): bool
    {
        return $this->amount < $other->amount;
    }

    public function amount(): float
    {
        return $this->amount / 100;
    }
}