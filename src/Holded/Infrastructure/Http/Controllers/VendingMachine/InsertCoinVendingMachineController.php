<?php
declare(strict_types=1);

namespace Holded\Infrastructure\Http\Controllers\VendingMachine;

use DomainException;
use Holded\Application\Commands\VendingMachine\InsertCoinVendingMachineCommand;
use Holded\Application\Services\VendingMachine\InsertCoinVendingMachineService;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(
    path: 'api/vending/insert-coin',
    name: 'insert_coin_vending_machine',
    methods: ['POST']
)]
final readonly class InsertCoinVendingMachineController
{
    public function __construct(
        private InsertCoinVendingMachineService $insertCoinVendingMachineService
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true) ?? [];
            $command = InsertCoinVendingMachineCommand::fromArray($data);

            $this->insertCoinVendingMachineService->execute($command);

            return new JsonResponse(
                data: [
                    'data' => [
                        'message' => 'Coin inserted successfully'
                    ]
                ],
                status: Response::HTTP_OK
            );

        } catch (InvalidArgumentException|DomainException $e) {
            return new JsonResponse(
                data: [
                    'error' => [
                        'message' => $e->getMessage()
                    ]
                ],
                status: Response::HTTP_BAD_REQUEST
            );

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