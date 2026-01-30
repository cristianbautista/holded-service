<?php
declare(strict_types=1);

namespace Holded\Domain\Models;

class Product
{
    private string $name;
    private float $price;
    private int $quantity;

    private function __construct(string $name, float $price, int $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public static function createProduct(string $name, float $price, int $quantity): self
    {
        return new self($name, $price, $quantity);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function decrement(): void
    {
        if ($this->quantity <= 0) {
            throw new \RuntimeException('Item out of stock');
        }

        $this->quantity--;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }

}