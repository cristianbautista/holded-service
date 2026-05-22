<?php

declare(strict_types=1);

namespace Holded\Domain\Models\Aggregate;

use DomainException;
use Holded\Domain\Exception\InsufficientFundsException;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;

final class VendingMachine
{
    private array $inventory;
    private array $prices;
    private Money $balance;

    private function __construct(array $inventory, array $prices, Money $balance = null)
    {
        $this->inventory = $inventory;
        $this->prices = $prices;
        $this->balance = $balance ?? Money::zero();
    }

    public static function create(
        array $inventory,
        array $prices,
        Money $balance = null
    ): VendingMachine
    {
        return new self(
            inventory: $inventory,
            prices: $prices,
            balance: $balance
        );
    }


    public function insertCoin(Money $coin): void
    {
        $this->balance = $this->balance->add($coin);
    }

    public function buyProduct(ProductId $productId): void
    {
        $id = $productId->value();

        if (!isset($this->inventory[$id])) {
            throw new DomainException("Product does not exist.", 404);
        }
        if ($this->inventory[$id] <= 0) {
            throw new DomainException("Product is out of stock.", 400);
        }

        $price = Money::fromPriceProduct($this->prices[$id]);
        if ($this->balance->isLessThan($price)) {
            throw new InsufficientFundsException("Insufficient funds. More money is required.", 400);
        }

        $this->inventory[$id]--;
        $this->balance = $this->balance->subtract($price);
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

    public function inventory(): array
    {
        return $this->inventory;
    }

    public function prices(): array
    {
        return $this->prices;
    }

    public function balance(): Money
    {
        return $this->balance;
    }
}