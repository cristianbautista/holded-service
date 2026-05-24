<?php
declare(strict_types=1);

namespace Holded\Tests\Application\InsertCoinVendingMachineServiceTest;

use Holded\Application\Services\VendingMachine\ListProductsVendingMachineService;
use Holded\Domain\Inventory;
use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Product;
use Holded\Infrastructure\Persistence\InMemory\InMemoryVendingRepository;
use PHPUnit\Framework\TestCase;

class ListProductsVendingMachineServiceTest extends TestCase
{
    private ListProductsVendingMachineService $service;

    public function test_it_lists_products_from_the_active_vending_machine(): void
    {
        $result = $this->service->listProducts();
        $expectedStructure = [
            [
                'id' => 'COKE',
                'price' => 1.50,
                'stock' => 5,
            ],
            [
                'id' => 'CHIPS',
                'price' => 1.00,
                'stock' => 2,
            ],
        ];

        $this->assertSame($expectedStructure, $result);
    }

    protected function setUp(): void
    {
        $product1 = Product::create(ProductId::fromString('COKE'), Money::fromPriceProduct(1.50), 5);
        $product2 = Product::create(ProductId::fromString('CHIPS'), Money::fromPriceProduct(1.00), 2);

        $inventory = Inventory::create([$product1, $product2]);
        $vendingMachine = VendingMachine::create($inventory);
        $repository = new InMemoryVendingRepository();
        $repository->save($vendingMachine);

        $this->service = new ListProductsVendingMachineService($repository);
    }
}