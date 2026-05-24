<?php
declare(strict_types=1);

namespace Holded\Tests\Application\Services;

use DomainException;
use Holded\Application\Services\VendingMachine\RefundMoneyVendingMachineService;
use Holded\Domain\Inventory;
use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Infrastructure\Persistence\InMemory\InMemoryVendingRepository;
use PHPUnit\Framework\TestCase;

class RefundMoneyVendingMachineServiceTest extends TestCase
{
    private InMemoryVendingRepository $repository;
    private RefundMoneyVendingMachineService $service;

    public function test_it_refunds_money_successfully_and_resets_vending_machine_balance(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]), Money::fromCoin(1.00));
        $this->repository->save($vendingMachine);

        $refundedAmount = $this->service->execute();

        $this->assertSame(1.00, $refundedAmount);

        $savedMachine = $this->repository->findActiveMachine();
        $this->assertSame(0.0, $savedMachine->balance()->amount());
    }

    public function test_it_fails_to_refund_if_vending_machine_has_no_balance_should_be_return_an_exception(): void
    {
        $this->expectException(DomainException::class);

        $vendingMachine = VendingMachine::create(Inventory::create([]), Money::zero());
        $this->repository->save($vendingMachine);


        $this->service->execute();
    }

    protected function setUp(): void
    {
        $this->repository = new InMemoryVendingRepository();
        $this->service = new RefundMoneyVendingMachineService($this->repository);
    }
}