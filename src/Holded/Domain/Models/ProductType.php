<?php
declare(strict_types = 1);

namespace Holded\Domain\Models;


enum ProductType: string
{
    case COKE = 'COKE';
    case WATER = 'WATER';
    case CHIPS = 'CHIPS';
}