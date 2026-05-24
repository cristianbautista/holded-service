<?php

declare(strict_types=1);

namespace Holded\Domain\Repository;

use Holded\Domain\Models\Aggregate\VendingMachine;

interface VendingRepositoryInterface
{
    public function findActiveMachine(): VendingMachine;

    public function save(VendingMachine $vendingMachine): void;
}