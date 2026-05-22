<?php
declare(strict_types=1);

namespace Holded\Infrastructure\Http\Controllers\VendingMachine;

use Holded\Application\Services\VendingMachine\ListProductsVendingMachineService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(
    path: '/api/vending/products',
    name: 'list_products_vending_machine',
    methods: ['GET']
)]
final readonly class ListProductsVendingMachineController
{
    public function __construct(
        private ListProductsVendingMachineService $listProductsVendingMachineService
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $data = $this->listProductsVendingMachineService->listProducts();

            return new JsonResponse([
                'data' => [
                    'products' => $data
                ]
            ]);

        } catch (Throwable $throwable) {
            return new JsonResponse(
                data: [
                    'error' => [
                        'message' => $throwable->getMessage()
                    ]
                ],
                status: Response::HTTP_BAD_REQUEST
            );
        }
    }

}