<?php
declare(strict_types=1);

namespace Holded\Application\Services\VendingMachine;

use Holded\Application\Commands\VendingMachine\InsertCoinVendingMachineCommand;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Repository\VendingRepositoryInterface;

final readonly class InsertCoinVendingMachineService
{
    public function __construct(
        private VendingRepositoryInterface $vendingMachineRepository
    )
    {
    }

    public function execute(InsertCoinVendingMachineCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->findActiveMachine();

        $vendingMachine->insertCoin(Money::fromCoin(coin: $command->coin()));

        $this->vendingMachineRepository->save($vendingMachine);
    }
}   
