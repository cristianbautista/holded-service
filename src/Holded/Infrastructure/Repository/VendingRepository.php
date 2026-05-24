<?php

declare(strict_types=1);

namespace Holded\Infrastructure\Repository;

use Holded\Domain\Inventory;
use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ProductType;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Models\ValueObject\ProductId;
use Holded\Domain\Product;
use Holded\Domain\Repository\VendingRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class VendingRepository implements VendingRepositoryInterface
{
    private const KEY_VENDING_MACHINE = "vending_machine";
    private const PRICE_COKE = 1.50;
    private const PRICE_WATER = 1.00;
    private const PRICE_CHIPS = 0.85;

    public function __construct(
        private readonly SessionInterface $session
    )
    {
    }

    public function findActiveMachine(): VendingMachine
    {
        if (!$this->session->has(self::KEY_VENDING_MACHINE)) {
            $initialProducts = [
                Product::create(ProductId::fromString(ProductType::COKE->value), Money::fromPriceProduct(self::PRICE_COKE), 5),
                Product::create(ProductId::fromString(ProductType::WATER->value), Money::fromPriceProduct(self::PRICE_WATER), 10),
                Product::create(ProductId::fromString(ProductType::CHIPS->value), Money::fromPriceProduct(self::PRICE_CHIPS), 0),
            ];

            $vendingInit = VendingMachine::create(
                Inventory::create($initialProducts),
                Money::zero()
            );

            $this->save($vendingInit);
        }

        $data = $this->session->get(self::KEY_VENDING_MACHINE);
        $products = [];

        foreach ($data['inventory'] as $item) {
            $products[] = Product::create(
                ProductId::fromString($item['id']),
                Money::fromPriceProduct($item['price']),
                $item['stock']
            );
        }

        return VendingMachine::create(
            Inventory::create($products),
            Money::fromPriceProduct((float)$data['balance'])
        );
    }

    public function save(VendingMachine $vendingMachine): void
    {
        $inventoryData = [];
        foreach ($vendingMachine->inventory()->all() as $product) {
            $inventoryData[] = [
                'id' => $product->id()->value,
                'price' => $product->price()->amount(),
                'stock' => $product->stock(),
            ];
        }

        $this->session->set(self::KEY_VENDING_MACHINE, [
            'inventory' => $inventoryData,
            'balance' => $vendingMachine->balance()->amount(),
        ]);

    }
}