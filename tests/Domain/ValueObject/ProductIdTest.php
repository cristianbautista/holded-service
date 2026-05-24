<?php
declare(strict_types=1);

namespace Holded\Tests\Domain\ValueObject;

use Holded\Domain\Models\ValueObject\ProductId;
use PHPUnit\Framework\TestCase;

class ProductIdTest extends TestCase
{
    public function test_it_creates_product_id_from_valid_string(): void
    {
        $id = ProductId::fromString('prod-123');

        $this->assertSame('PROD-123', $id->value);
    }

    public function test_it_converts_value_to_uppercase(): void
    {
        $id = ProductId::fromString('abcdef');

        $this->assertSame('ABCDEF', $id->value);
    }
}