<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingEntryPositionDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryPositionDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingEntryPositionRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingEntryPositionRequest;
use App\Modules\Accounting\Http\Resources\AccountingEntryPositionResource;
use App\Modules\Accounting\Models\AccountingEntryPosition;
use App\Modules\Accounting\Services\AccountingEntryPositionService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingEntryPositionController extends ApiController
{
    public function __construct(
        protected AccountingEntryPositionService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingEntryPositionResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Posiciones de asiento contable obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingEntryPositionRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingEntryPositionDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingEntryPositionResource($item))->resolve(),
            'Accounting entry position created successfully.',
            201,
        );
    }

    public function show(int $accountingEntryPosition): JsonResponse
    {
        $item = $this->service->findOrFail($accountingEntryPosition);

        return $this->successResponse(
            (new AccountingEntryPositionResource($item))->resolve(),
            'Accounting entry position retrieved successfully.',
        );
    }

    public function update(UpdateAccountingEntryPositionRequest $request, AccountingEntryPosition $accountingEntryPosition): JsonResponse
    {
        $item = $this->service->update($accountingEntryPosition, UpdateAccountingEntryPositionDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingEntryPositionResource($item))->resolve(),
            'Accounting entry position updated successfully.',
        );
    }

    public function destroy(AccountingEntryPosition $accountingEntryPosition): JsonResponse
    {
        $this->service->delete($accountingEntryPosition);

        return $this->successResponse(null, 'Accounting entry position soft deleted successfully.');
    }

    public function restore(int $accountingEntryPosition): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingEntryPosition));

        return $this->successResponse(
            (new AccountingEntryPositionResource($item))->resolve(),
            'Accounting entry position restored successfully.',
        );
    }
}
