<?php

declare(strict_types=1);

namespace App\Tests\Domain\ValueObject;

use Holded\Domain\Models\ValueObject\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public static function coinsProvider(): array
    {
        return [
            '5 cents (valid)' => [0.05, true],
            '10 cents (valid)' => [0.10, true],
            '20 cents (valid)' => [0.20, true],
            '50 cents (valid)' => [0.50, true],
            '1 euro (valid)' => [1.00, true],
            '2 euros (valid)' => [2.00, true],
            '1 cent (invalid)' => [0.01, false],
            '3 cents (invalid)' => [0.03, false],
            '99 cents (invalid)' => [0.99, false],
            '3 euros (invalid)' => [3.00, false],
        ];
    }

    public static function operationsProvider(): array
    {
        return [
            'add simple' => ['add', 10.00, 5.00, 15.00],
            'subtract simple' => ['subtract', 10.00, 5.00, 5.00],
            'subtract decimal' => ['subtract', 10.50, 0.25, 10.25],
            'subtract to zero' => ['subtract', 5.00, 5.00, 0.00],
        ];
    }

    public static function comparisonProvider(): array
    {
        return [
            'less than' => [5.00, 10.00, true],
            'not less than' => [10.00, 5.00, false],
            'equal' => [5.00, 5.00, false],
        ];
    }

    public function test_it_creates_money_correctly(): void
    {
        $this->assertSame(10.25, Money::fromPriceProduct(10.25)->amount());
        $this->assertSame(0.0, Money::zero()->amount());
    }

    public function test_it_rejects_negative_values(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Money::fromPriceProduct(-1.00);
    }

    /**
     * @dataProvider coinsProvider
     */
    public function test_it_handles_coins_validation(float $coin, bool $isValid): void
    {
        if (!$isValid) {
            $this->expectException(InvalidArgumentException::class);
        }

        $money = Money::fromCoin($coin);
        $this->assertSame(round($coin, 2), $money->amount());
    }

    /**
     * @dataProvider operationsProvider
     */
    public function test_it_performs_math_operations(string $method, float $a, float $b, float $expected): void
    {
        $result = Money::fromPriceProduct($a)->$method(Money::fromPriceProduct($b));
        $this->assertSame($expected, $result->amount());
    }

    /**
     * @dataProvider comparisonProvider
     */
    public function test_it_checks_if_is_less_than(float $a, float $b, bool $expected): void
    {
        $this->assertSame($expected, Money::fromPriceProduct($a)->isLessThan(Money::fromPriceProduct($b)));
    }
}