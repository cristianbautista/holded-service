<?php
declare(strict_types=1);

namespace Holded\Application\Commands\VendingMachine;

use InvalidArgumentException;

final readonly class BuyProductVendingMachineCommand
{
    public function __construct(
        public string $productIdRaw
    )
    {
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['data']['product_key'])) {
            throw new InvalidArgumentException('The "product_key" parameter is required.');
        }

        return new self($data['data']['product_key']);
    }
}
