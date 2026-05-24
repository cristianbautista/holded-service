<?php

declare(strict_types=1);

namespace Holded\Tests\Domain;

use DomainException;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public static function invalidStockProvider(): array
    {
        return [
            'negative stock -1' => [-1],
            'negative stock -100' => [-100],
        ];
    }

    public function test_it_creates_a_product_correctly(): void
    {
        $id = ProductId::fromString('prod-123');
        $price = Money::fromPriceProduct(19.99);
        $stock = 10;

        $product = Product::create($id, $price, $stock);

        $this->assertSame($id, $product->id());
        $this->assertSame($price, $product->price());
        $this->assertSame($stock, $product->stock());
        $this->assertTrue($product->hasStock());
    }

    /**
     * @dataProvider invalidStockProvider
     */
    public function test_it_rejects_negative_stock_on_creation(int $invalidStock): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Stock cannot be negative.");

        Product::create(
            ProductId::fromString('valid-id'),
            Money::fromPriceProduct(10.00),
            $invalidStock
        );
    }

    public function test_it_reduces_stock_correctly(): void
    {
        $product = Product::create(
            ProductId::fromString('id'),
            Money::fromPriceProduct(10.00),
            2
        );

        $product->reduceStock();

        $this->assertSame(1, $product->stock());
        $this->assertTrue($product->hasStock());
    }

    public function test_it_knows_when_it_runs_out_of_stock(): void
    {
        $product = Product::create(
            ProductId::fromString('id'),
            Money::fromPriceProduct(10.00),
            1
        );

        $product->reduceStock();

        $this->assertSame(0, $product->stock());
        $this->assertFalse($product->hasStock());
    }

    public function test_it_fails_to_reduce_stock_when_out_of_stock(): void
    {
        $product = Product::create(
            ProductId::fromString('id'),
            Money::fromPriceProduct(10.00),
            0
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Product is out of stock.");

        $product->reduceStock();
    }
}