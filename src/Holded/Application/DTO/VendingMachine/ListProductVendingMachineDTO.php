<?php
declare(strict_types = 1);

namespace Holded\Application\DTO\VendingMachine;

final class ListProductVendingMachineDTO
{
    private array $data = [];

    private function __construct(
        private array $products,

    ) {


    }

    public static function fromProducts(array $products): self
    {
        return new self($products);
    }
    


}