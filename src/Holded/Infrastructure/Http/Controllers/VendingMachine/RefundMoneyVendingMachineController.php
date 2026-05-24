<?php
declare(strict_types=1);

namespace Holded\Infrastructure\Http\Controllers\VendingMachine;

use Holded\Application\Services\VendingMachine\RefundMoneyVendingMachineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/api/vending/refund', name: 'refund_money_vending_machine', methods: ['POST'])]
final class RefundMoneyVendingMachineController extends AbstractController
{
    public function __construct(
        private readonly RefundMoneyVendingMachineService $refundMoneyVendingMachineService
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $amount = $this->refundMoneyVendingMachineService->execute();
            return $this->json(
                data: [
                    'data' => [
                        'message' => 'Money refunded successfully',
                        'amount' => $amount
                    ]

                ],
                status: JsonResponse::HTTP_OK
            );
        } catch (Throwable $throwable) {
            return $this->json(
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