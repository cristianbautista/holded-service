<?php

declare(strict_types=1);

namespace Holded\Application\Services\VendingMachine;

use DomainException;
use Holded\Application\Commands\VendingMachine\BuyProductVendingMachineCommand;
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
        $vendingMachine = $this->vendingMachineRepository->findActiveMachine();
        
        $productKey = strtoupper(trim($command->productIdRaw));

        $productsInInventory = $vendingMachine->inventory()->all();

        if (!isset($productsInInventory[$productKey])) {
            throw new DomainException(
                sprintf("The product with id %s is not in the inventory.",
                    $productKey,
                ),
                404
            );
        }
        $productToBuy = $productsInInventory[$productKey];

        $vendingMachine->buyProduct($productToBuy);

        $this->vendingMachineRepository->save($vendingMachine);
    }
}