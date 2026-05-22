<?php
declare(strict_types=1);

namespace Holded\Application\Services\VendingMachine;

use Holded\Application\DTO\VendingMachine\ListProductVendingMachineDTO;
use Holded\Domain\Repository\VendingRepositoryInterface;

final readonly class ListProductsVendingMachineService
{
    public function __construct(
        private VendingRepositoryInterface $vendingRepository
    )
    {
    }

    public function listProducts(): array
    {
        $machine = $this->vendingRepository->vending();

        return ListProductVendingMachineDTO::fromMachine(machine: $machine)
            ->toArray();
    }
}