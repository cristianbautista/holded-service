<?php
declare(strict_types = 1);

namespace Holded\Infrastructure\Http\Controllers\VendingMachine;

use Symfony\Component\Routing\Annotation\Route;
use Holded\Application\Services\VendingMachine\RefundMoneyVendingMachineService;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route(path:'/api/vending/refund', name: 'refund_money_vending_machine', methods: ['POST'])]
final readonly class RefundMoneyVendingMachineController
{
    public function __construct(
        private RefundMoneyVendingMachineService $refundMoneyVendingMachineService
    ) {
    }
    public function __invoke(): JsonResponse
    {
        try {
            $amount = $this->refundMoneyVendingMachineService->execute();
            return new JsonResponse(
                data: [
                    'data' => [
                        'message' => 'Money refunded successfully',
                        'amount' => $amount
                    ]
                    
                ],
                status: JsonResponse::HTTP_OK
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