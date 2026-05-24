<?php
declare(strict_types=1);

namespace App\Tests\Domain\ValueObject;

use Holded\Domain\Models\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_should_create_money_object()
    {
        $money = Money::fromCoin(coin: 0.50);
        $this->assertEquals(0.50, $money->amount());

        return $this;
    }

}