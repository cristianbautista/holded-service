<?php
declare(strict_types=1);

namespace Holded\Tests\Application\Services;

use Holded\Application\Commands\VendingMachine\InsertCoinVendingMachineCommand;
use Holded\Application\Services\VendingMachine\InsertCoinVendingMachineService;
use Holded\Domain\Inventory;
use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Infrastructure\Persistence\InMemory\InMemoryVendingRepository;
use PHPUnit\Framework\TestCase;

class InsertCoinVendingMachineServiceTest extends TestCase
{
    private InMemoryVendingRepository $repository;
    private InsertCoinVendingMachineService $service;

    public function test_it_inserts_a_coin_and_updates_vending_machine_balance(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]), Money::zero());
        $this->repository->save($vendingMachine);

        $command = new InsertCoinVendingMachineCommand(coin: 1.00);

        $this->service->execute($command);
        $savedMachine = $this->repository->findActiveMachine();

        $this->assertSame(1.00, $savedMachine->balance()->amount());
    }

    public function test_it_accumulates_balance_when_multiple_coins_are_inserted(): void
    {

        $vendingMachine = VendingMachine::create(Inventory::create([]), Money::fromCoin(0.50));
        $this->repository->save($vendingMachine);

        $command = new InsertCoinVendingMachineCommand(coin: 2.00);
        $this->service->execute($command);

        $savedMachine = $this->repository->findActiveMachine();
        $this->assertSame(2.50, $savedMachine->balance()->amount());
    }

    protected function setUp(): void
    {
        $this->repository = new InMemoryVendingRepository();
        $this->service = new InsertCoinVendingMachineService($this->repository);
    }

}