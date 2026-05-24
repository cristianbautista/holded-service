<?php

declare(strict_types=1);

namespace Holded\Application\DTO\VendingMachine;

use Holded\Domain\Models\Aggregate\VendingMachine;

final readonly class ListProductVendingMachineDTO
{
    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromMachine(VendingMachine $machine): self
    {
        $data = [];
        
        foreach ($machine->inventory()->all() as $product) {
            $data[] = [
                'id' => $product->id()->value,
                'price' => $product->price()->amount(),
                'stock' => $product->stock(),
            ];
        }

        return new self($data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}