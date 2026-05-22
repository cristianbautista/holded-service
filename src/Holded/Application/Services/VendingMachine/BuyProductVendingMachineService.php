<?php
declare(strict_types=1);

namespace Holded\Application\Services\VendingMachine;

use Holded\Application\Commands\VendingMachine\BuyProductVendingMachineCommand;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Repository\VendingRepositoryInterface;

final readonly class BuyProductVendingMachineService
{
    public function __construct(
        private VendingRepositoryInterface $vendingMachineRepository
    )
    {
    }

    public function execute(BuyProductVendingMachineCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->vending();

        $vendingMachine->buyProduct(productId: ProductId::fromString($command->productIdRaw()));

        $this->vendingMachineRepository->save($vendingMachine);

    }
}