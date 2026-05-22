<?php

declare(strict_types = 1);

namespace Holded\Infrastructure\Repository;

use Holded\Domain\Models\Aggregate\VendingMachine;
use Holded\Domain\Models\ValueObject\Money;
use Holded\Domain\Repository\VendingRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Holded\Domain\Models\ProductType;

final class VendingRepository implements VendingRepositoryInterface
{
    private const KEY_VENDING_MACHINE = "vending_machine";

    private const PRICE_COKE = 1.50;
    private const PRICE_WATER = 1.00;
    private const PRICE_CHIPS = 0.85;

    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function vending(): VendingMachine
    {
        $session = $this->session();

        if (!$session->has(self::KEY_VENDING_MACHINE)) {
            $vendingInit = VendingMachine::create(
                [
                    ProductType::COKE => 5,
                    ProductType::WATER => 10,
                    ProductType::CHIPS => 0
                ],
                [
                    ProductType::COKE  => Money::fromCoin(self::PRICE_COKE),
                    ProductType::WATER => Money::fromCoin(self::PRICE_WATER),
                    ProductType::CHIPS => Money::fromCoin(self::PRICE_CHIPS),

                ],
                Money::zero()
            );
            $this->save($vendingInit);
        }

        $data = $session->get(self::KEY_VENDING_MACHINE);

        return VendingMachine::create(
            $data['inventory'], 
            $data['prices'], 
            $data['balance']
        );
    }

    public function save(VendingMachine $vendingMachine): void
    {
        $this->session()->set(self::KEY_VENDING_MACHINE, [
            'inventory' => $vendingMachine->inventory(),
            'prices'    => $vendingMachine->prices(),
            'balance'   => $vendingMachine->balance(),
        ]);
    }

    private function session(): SessionInterface
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (null === $currentRequest) {
            throw new LogicException("Session cannot be accessed outside the context of an HTTP request");
        }

        return $currentRequest->getSession();
    }


}