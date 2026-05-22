<?php

declare(strict_types = 1);

namespace Holded\Application\Services\VendingMachine;

use Holded\Application\Commands\VendingMachine\BuyProductVendingMachineCommand;
use Holded\Domain\Repository\VendingRepositoryInterface;
use Holded\Domain\Models\ValueObject\ProductId;


final readonly class BuyProductVendingMachineService
{
    public function __construct(
       private VendingRepositoryInterface $vendingMachineRepository
    ) {
    }

    public function __invoke(BuyProductVendingMachineCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->vending();

        $vendingMachine->buyProduct(ProductId::fromString($command->product()));

        $this->vendingMachineRepository->save($vendingMachine);

    }
}