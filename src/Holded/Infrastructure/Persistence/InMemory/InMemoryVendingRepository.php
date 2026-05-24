<?php
declare(strict_types=1);

namespace Holded\Infrastructure\Persistence\InMemory;

use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Repository\VendingRepositoryInterface;
use RuntimeException;

class InMemoryVendingRepository implements VendingRepositoryInterface
{
    private ?VendingMachine $machine = null;

    public function save(VendingMachine $vendingMachine): void
    {
        $this->machine = $vendingMachine;
    }

    public function findActiveMachine(): VendingMachine
    {
        if ($this->machine === null) {
            throw new RuntimeException("No active vending machine found in memory.");
        }

        return $this->machine;
    }
}