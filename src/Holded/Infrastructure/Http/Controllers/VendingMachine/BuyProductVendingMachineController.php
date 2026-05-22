<?php

namespace Holded\Infrastructure\Http\Controllers\VendingMachine;

use Holded\Application\Commands\VendingMachine\BuyProductVendingMachineCommand;
use Holded\Domain\Exception\InsufficientFundsException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Holded\Application\Services\VendingMachine\BuyProductVendingMachineService;

#[Route('/api/vending/products/buy', name: 'vending_buy_product', methods: ['POST'])]
final readonly class BuyProductVendingMachineController
{
    public function __construct(
        private BuyProductVendingMachineService $buyProductVendingMachineService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true) ?? [];

            $command = BuyProductVendingMachineCommand::fromArray($data);
            $this->buyProductVendingMachineService->execute($command);

            return new JsonResponse(
                data: [
                    'data' => [
                        'message' => 'Product bought successfully'
                    ]
                ],
                status: JsonResponse::HTTP_OK
            );
        } catch (InsufficientFundsException $e) {
            return new JsonResponse(
                data: [
                    'error' => [
                        'message' => $e->getMessage()
                    ]
                ],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (\Throwable $throwable) {
            return new JsonResponse(
                data: [
                    'error' => [
                        'message' => $throwable->getMessage()
                    ]
                ],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
