<?php
declare(strict_types=1);

namespace App\Tests\Domain;

use Holded\Domain\Inventory;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Product;
use PHPUnit\Framework\TestCase;

class InventoryTest extends TestCase
{
    public function test_it_creates_an_empty_inventory(): void
    {
        $inventory = Inventory::create([]);
        $this->assertEmpty($inventory->all());
    }

    public function test_it_creates_inventory_indexed_by_product_id(): void
    {
        $inventory = Inventory::create([
            $product1 = $this->product('prod-100'),
            $product2 = $this->product('prod-200'),
        ]);

        $productsInInventory = $inventory->all();

        $this->assertCount(2, $productsInInventory);
        $this->assertSame($product1, $productsInInventory['PROD-100']);
        $this->assertSame($product2, $productsInInventory['PROD-200']);
    }

    private function product(string $id): Product
    {

        return Product::create(ProductId::fromString($id), Money::fromPriceProduct(10.00), 1);
    }

}