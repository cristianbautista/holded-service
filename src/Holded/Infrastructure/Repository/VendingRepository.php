<?php
declare(strict_types=1);

namespace Holded\Infrastructure\Repository;

use Holded\Application\VendingService;
use Holded\Domain\Repository\VendingRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VendingRepository implements VendingRepositoryInterface
{
    private const SESSION_KEY = 'vending_state';
    private VendingService $machine;
    private ?SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
        $this->machine = new VendingService();
        $this->load();
    }

    public function vending(): VendingService
    {
        return $this->machine;
    }

    public function save(): void
    {
        $this->session?->set(self::SESSION_KEY, $this->machine->exportState());
    }

    public function load(): void
    {
        if ($this->session) {
            $state = $this->session->get(self::SESSION_KEY);
            if ($state) {
                $this->machine->loadState($state);
            }
        }
    }

    public function clear(): void
    {
        $this->session?->remove(self::SESSION_KEY);
        $this->machine = new VendingService();
    }
}
