<?php

declare(strict_types=1);

namespace Holded\Domain\Models\Aggregate;

use DomainException;
use Holded\Domain\Exception\InsufficientFundsException;
use Holded\Domain\Inventory;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Product;

final class VendingMachine
{
    private Inventory $inventory;
    private Money $balance;

    private function __construct(Inventory $inventory, Money $balance = null)
    {
        $this->inventory = $inventory;
        $this->balance = $balance ?? Money::zero();
    }

    public static function create(Inventory $inventory, Money $balance = null): VendingMachine
    {
        return new self(inventory: $inventory, balance: $balance);
    }

    public function insertCoin(Money $coin): void
    {
        $this->balance = $this->balance->add($coin);
    }

    public function buyProduct(Product $product): void
    {
        if (!$product->hasStock()) {
            throw new DomainException("Product is out of stock.", 400);
        }

        if ($this->balance->isLessThan($product->price())) {
            throw new InsufficientFundsException("Insufficient funds. More money is required.", 400);
        }

        $product->reduceStock();
        $this->balance = $this->balance->subtract($product->price());
    }

    public function refund(): Money
    {
        if ($this->balance->amount() === 0.0) {
            throw new DomainException("Insufficient funds. More money is required.");
        }
        $refundedAmount = $this->balance;
        $this->balance = Money::zero();

        return $refundedAmount;
    }

    public function inventory(): Inventory
    {
        return $this->inventory;
    }

    public function balance(): Money
    {
        return $this->balance;
    }
}