<?php
declare(strict_types=1);

namespace Holded\Infrastructure\Http\Controllers;

use Holded\Application\VendingService;
use Holded\Domain\Models\Coin;
use Holded\Infrastructure\Repository\VendingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendingController extends AbstractController
{
    public function __construct(private VendingRepository $repository) {}

    #[Route('/insert/{amount}', methods: ['POST'])]
    public function insert(float $amount): JsonResponse
    {
        $machine = $this->repository->vending();

        $coin = Coin::create($amount);
        $machine->insertCoin($coin);

        $this->repository->save();

        return $this->json(['status' => 204]);
    }

    #[Route('/vend/{product}', methods: ['POST'])]
    public function vend(string $product): JsonResponse
    {
        $machine = $this->repository->vending();

        try {
            $result = $machine->vend($product);
            $this->repository->save();

            return $this->json(['data' => $result, 'status' => 200]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/return', methods: ['POST'])]
    public function returnCoin(): JsonResponse
    {
        $machine = $this->repository->vending();

        $coins = $machine->returnCoins();
        $this->repository->save();

        $coinsArray = [];
        foreach ($coins as $coin) {
            $coinsArray[] = $coin->value();
        }

        return $this->json(['returned_coins' => $coinsArray, 'status' => 200]);
    }

}
