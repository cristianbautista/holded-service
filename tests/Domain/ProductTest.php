<?php
declare(strict_types=1);
namespace App\Tests\Domain;

use Holded\Domain\Models\Product;
use PHPUnit\Framework\TestCase;


class ProductTest extends TestCase
{
    public function test_create_product()
    {
        $product = Product::createProduct('Test Product', 10.99, 5);
        $this->assertSame('Test Product', $product->name());
        $this->assertSame(10.99, $product->price());
        $this->assertEquals(5, $product->quantity());
    }

    public function test_create_product_should_be_return_to_array()
    {
        $product = Product::createProduct('Test Product', 10.99, 5);
        $this->assertEquals([
            'name' => 'Test Product',
            'price' => 10.99,
            'quantity' => 5,
        ], $product->toArray());
    }
}