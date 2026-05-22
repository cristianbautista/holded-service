<?php

declare(strict_types=1);

namespace Holded\Application\Commands\VendingMachine;

use InvalidArgumentException;

final readonly class InsertCoinVendingMachineCommand
{
    public function __construct(
        private float $coin
    )
    {
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['data']['coin'])) {
            throw new InvalidArgumentException('The "coin" parameter is required.');
        }

        return new self((float)$data['data']['coin']);
    }

    public function coin(): float
    {
        return $this->coin;
    }
}