<?php
declare(strict_types=1);

namespace App\Tests\Domain;

use Holded\Domain\Models\Coin;
use PHPUnit\Framework\TestCase;


class CoinTest extends TestCase
{
    /**
     * @dataProvider invalidCoinValueProvider
     */
    public function test_invalid_coin_value_return_an_exception(float $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Coin::create($value);
    }

    public static function invalidCoinValueProvider(): array
    {
        return [
            'zero value' => [0.00],
            'fifty cents' => [0.50],
            'negative value' => [-0.05],
            'invalid decimal' => [0.03],
        ];
    }

    /**
     * @dataProvider validCoinValueProvider
     */
    public function test_valid_coin_value(float $value): void
    {
        $coin = Coin::create($value);
        $this->assertEquals($value, $coin->value());
    }

    public static function validCoinValueProvider(): array
    {
        return [
            'five cents' => [0.05],
            'ten cents' => [0.10],
            'one euro' => [1.00],
        ];
    }
}