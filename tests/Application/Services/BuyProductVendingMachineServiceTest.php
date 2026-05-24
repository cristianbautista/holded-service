<?php

declare(strict_types=1);

namespace Holded\Tests\Application\InsertCoinVendingMachineServiceTest;

use DomainException;
use Holded\Application\Commands\VendingMachine\BuyProductVendingMachineCommand;
use Holded\Application\Services\VendingMachine\BuyProductVendingMachineService;
use Holded\Domain\Inventory;
use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Product;
use Holded\Infrastructure\Persistence\InMemory\InMemoryVendingRepository;
use PHPUnit\Framework\TestCase;

class BuyProductVendingMachineServiceTest extends TestCase
{
    private InMemoryVendingRepository $repository;
    private BuyProductVendingMachineService $service;

    public function test_it_buys_a_product_successfully_updating_vending_machine_state(): void
    {

        $product = Product::create(ProductId::fromString('COKE'), Money::fromPriceProduct(1.50), 2);
        $vendingMachine = VendingMachine::create(
            Inventory::create([$product]),
            Money::fromCoin(2.00)
        );
        $this->repository->save($vendingMachine);

        $command = new BuyProductVendingMachineCommand(productIdRaw: '  coke  ');

        $this->service->execute($command);

        $savedMachine = $this->repository->findActiveMachine();
        $this->assertSame(0.50, $savedMachine->balance()->amount());

        $savedProduct = $savedMachine->inventory()->all()['COKE'];
        $this->assertSame(1, $savedProduct->stock());
    }

    public function test_it_fails_if_product_does_not_exist_in_inventory(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]));
        $this->repository->save($vendingMachine);

        $command = new BuyProductVendingMachineCommand(productIdRaw: 'NON-EXISTENT');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("The product with id NON-EXISTENT is not in the inventory.");
        $this->expectExceptionCode(404);

        $this->service->execute($command);
    }

    protected function setUp(): void
    {
        $this->repository = new InMemoryVendingRepository();
        $this->service = new BuyProductVendingMachineService($this->repository);
    }
}