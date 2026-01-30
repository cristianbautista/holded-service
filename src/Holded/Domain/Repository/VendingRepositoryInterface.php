<?php
declare(strict_types=1);
namespace Holded\Domain\Repository;

use Holded\Application\VendingService;

interface VendingRepositoryInterface
{
    public function vending(): VendingService;
    public function save(): void;
    public function load(): void;
    public function clear(): void;
}