<?php

declare(strict_types=1);

namespace Holded\Tests\Domain\Aggregate;

use DomainException;
use Holded\Domain\Exception\InsufficientFundsException;
use Holded\Domain\Inventory;
use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Product;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public function test_it_initializes_with_empty_balance_by_default(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]));

        $this->assertSame(0.0, $vendingMachine->balance()->amount());
    }

    public function test_it_allows_inserting_coins_and_accumulates_balance(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]));

        $vendingMachine->insertCoin(Money::fromCoin(1.00));
        $vendingMachine->insertCoin(Money::fromCoin(0.50));

        $this->assertSame(1.50, $vendingMachine->balance()->amount());
    }

    public function test_it_allows_buying_a_product_successfully(): void
    {

        $product = $this->createProduct('PROD-1', 1.50, 1);
        $inventory = Inventory::create([$product]);
        $vendingMachine = VendingMachine::create($inventory);

        $vendingMachine->insertCoin(Money::fromCoin(2.00));


        $vendingMachine->buyProduct($product);


        $this->assertSame(0, $product->stock());
        $this->assertSame(0.50, $vendingMachine->balance()->amount());
    }

    private function createProduct(string $id, float $price, int $stock): Product
    {
        return Product::create(
            ProductId::fromString($id),
            Money::fromPriceProduct($price),
            $stock
        );
    }

    public function test_it_fails_to_buy_product_if_it_is_out_of_stock(): void
    {
        $product = $this->createProduct('PROD-1', 1.00, 0);
        $vendingMachine = VendingMachine::create(Inventory::create([$product]));
        $vendingMachine->insertCoin(Money::fromCoin(2.00));

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Product is out of stock.");

        $vendingMachine->buyProduct($product);
    }

    public function test_it_fails_to_buy_product_if_funds_are_insufficient(): void
    {
        $this->expectException(InsufficientFundsException::class);
        $product = $this->createProduct('PROD-1', 2.00, 1);
        $vendingMachine = VendingMachine::create(Inventory::create([$product]));

        $vendingMachine->insertCoin(Money::fromCoin(1.00));

        $vendingMachine->buyProduct($product);
    }

    public function test_it_refunds_accumulated_balance_and_resets_to_zero(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]));
        $vendingMachine->insertCoin(Money::fromCoin(2.00));

        $refundedMoney = $vendingMachine->refund();

        $this->assertSame(2.00, $refundedMoney->amount());
        $this->assertSame(0.0, $vendingMachine->balance()->amount());
    }

    public function test_it_fails_to_refund_if_balance_is_already_zero(): void
    {
        $vendingMachine = VendingMachine::create(Inventory::create([]));

        $this->expectException(DomainException::class);

        $vendingMachine->refund();
    }
}