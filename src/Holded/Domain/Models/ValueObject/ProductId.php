<?php

declare(strict_types = 1);

namespace Holded\Domain\Models\ValueObject;

use InvalidArgumentException;

final class ProductId
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException("This value is not empty.");
        }

        $this->value = strtoupper($value);
    }

    public static function fromString(string $value): ProductId
    {
        return new self($value);
    }


    public function value(): string
    {
        return $this->value;
    }
}