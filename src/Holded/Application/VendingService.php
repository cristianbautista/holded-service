<?php
declare(strict_types=1);
namespace Holded\Application;

use Holded\Application\Exception\Vending\InvalidVendingException;
use Holded\Domain\Models\Coin;
use Holded\Domain\Models\Product;


class VendingService
{
    /** @var Product[] */
    private array $products = [];

    /** @var array<float,int> */
    private array $availableChange = [];

    /** @var Coin[] */
    private array $insertedCoins = [];

    public function __construct()
    {
        $this->products = [
            'water' => Product::createProduct('Water', 0.65, 10),
            'juice' => Product::createProduct ('Juice', 1.00, 10),
            'soda'  => Product::createProduct('Soda', 1.50, 10),
        ];

        $this->availableChange = [
            0.25 => 10,
            0.10 => 10,
            0.05 => 10,
        ];
    }

    public function insertCoin(Coin $coin): void
    {
        $this->insertedCoins[] = $coin;

        if ($coin->isChangeCoin()) {
            $this->availableChange[$coin->value()]++;
        }
    }


    public function returnCoins(): array
    {
        $coins = $this->insertedCoins;
        $this->insertedCoins = [];

        return $coins;
    }


    public function vend(string $selector): array
    {
        if (!isset($this->products[$selector])) {
            throw new InvalidVendingException('Invalid item selection');
        }

        $product = $this->products[$selector];

        if ($product->quantity() <= 0) {
            throw new InvalidVendingException('Item out of stock');
        }

        $insertedAmount = $this->insertedAmount();

        if ($this->insertedAmount() < $product->price()) {
            throw new InvalidVendingException('Not enough money inserted');
        }

        $changeAmount = round($insertedAmount - $product->price(), 2);
        $changeCoins  = $this->calculateChange($changeAmount);

        $product->decrement();
        $this->insertedCoins = [];

        return [
            'product'   => $product->name(),
            'change' => array_map(fn(Coin $coin) => $coin->value(), $changeCoins),
        ];
    }

    private function insertedAmount(): float
    {
        $total = 0.0;

        foreach ($this->insertedCoins as $coin) {
            $total += $coin->value();
        }

        return $total;
    }

    private function calculateChange(float $amount): array
    {
        $result = [];

        foreach ([0.25, 0.10, 0.05] as $value) {
            while ($amount >= $value && $this->availableChange[$value] > 0) {
                $amount = round($amount - $value, 2);
                $this->availableChange[$value]--;
                $result[] = Coin::create($value);
            }
        }

        if ($amount > 0) {
            throw new InvalidVendingException('Not enough change available');
        }

        return $result;
    }

    public function exportState(): array
    {
        $productsData = array_map(function ($product) {
            return [
                'name'     => $product->name(),
                'price'    => $product->price(),
                'quantity' => $product->quantity(),
            ];
        }, $this->products);

        $coinsData = [];
        foreach ($this->insertedCoins as $coin) {
            $coinsData[] = $coin->value();
        }

        return [
            'products' => $productsData,
            'availableChange' => $this->availableChange,
            'insertedCoins' => $coinsData,
        ];
    }

    public function loadState(array $state): void
    {
        $this->products = [];
        foreach ($state['products'] as $key => $data) {
            $this->products[$key] = Product::createProduct($data['name'], $data['price'], $data['quantity']);
        }

        $this->availableChange = $state['availableChange'] ?? [];

        $this->insertedCoins = [];
        if (isset($state['insertedCoins'])) {
            foreach ($state['insertedCoins'] as $value) {
                $this->insertedCoins[] = Coin::create($value);
            }
        }
    }

}