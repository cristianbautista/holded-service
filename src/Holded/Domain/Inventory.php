<?php

namespace Holded\Domain;

final class Inventory
{
    /** @var array<string, Product> */
    private array $products = [];

    /**
     * @param Product[] $products
     */
    private function __construct(array $products)
    {
        foreach ($products as $product) {
            $this->products[$product->id()->value] = $product;
        }
    }

    public static function create(array $products): self
    {
        return new self($products);
    }

    public function all(): array
    {
        return $this->products;
    }
}