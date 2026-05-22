<?php
declare(strict_types=1);

namespace Holded\Application\DTO\VendingMachine;

use Holded\Domain\Models\Aggregate\VendingMachine;

final class ListProductVendingMachineDTO
{
    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromMachine(VendingMachine $machine): self
    {
        $data = [];
        foreach ($machine->inventory() as $id => $stock) {
            $data[] = [
                'id' => $id,
                'price' => $machine->prices()[$id],
                'stock' => $stock,
            ];
        }

        return new self($data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
