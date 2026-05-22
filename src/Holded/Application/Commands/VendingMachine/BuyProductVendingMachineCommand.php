<?php
declare(strict_types=1);

namespace Holded\Application\Commands\VendingMachine;

use InvalidArgumentException;

final readonly class BuyProductVendingMachineCommand
{
    public function __construct(
        private string $productIdRaw
    )
    {
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['product_key'])) {
            throw new InvalidArgumentException('The "product_key" parameter is required.');
        }

        return new self($data['product_key']);
    }

    public function productIdRaw(): string
    {
        return $this->productIdRaw;
    }
}
