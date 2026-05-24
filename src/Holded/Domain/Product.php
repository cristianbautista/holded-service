<?php
declare(strict_types=1);

namespace Holded\Domain;

use DomainException;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;

final class Product
{
    private ProductId $id;
    private Money $price;
    private int $stock;

    private function __construct(ProductId $id, Money $price, int $stock)
    {
        if ($stock < 0) {
            throw new DomainException("Stock cannot be negative.", 400);
        }

        $this->id = $id;
        $this->price = $price;
        $this->stock = $stock;
    }

    public static function create(ProductId $id, Money $price, int $stock): self
    {
        return new self($id, $price, $stock);
    }


    public function id(): ProductId
    {
        return $this->id;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function reduceStock(): void
    {
        if (!$this->hasStock()) {
            throw new DomainException("Product is out of stock.", 400);
        }
        $this->stock--;
    }

    public function hasStock(): bool
    {
        return $this->stock > 0;
    }

    public function stock(): int
    {
        return $this->stock;
    }
}
