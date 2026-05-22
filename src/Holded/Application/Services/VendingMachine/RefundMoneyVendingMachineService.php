<?php
declare(strict_types=1);

namespace Holded\Application\Services\VendingMachine;

use Holded\Domain\Repository\VendingRepositoryInterface;

final readonly class RefundMoneyVendingMachineService
{
    public function __construct(
        private VendingRepositoryInterface $vendingMachineRepository
    )
    {
    }

    public function execute(): float
    {
        $vendingMachine = $this->vendingMachineRepository->vending();

        $refund = $vendingMachine->refund();

        $this->vendingMachineRepository->save(vendingMachine: $vendingMachine);

        return $refund->amount();
    }
}