<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingMomentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingMomentDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingMomentRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingMomentRequest;
use App\Modules\Accounting\Http\Resources\AccountingMomentResource;
use App\Modules\Accounting\Models\AccountingMoment;
use App\Modules\Accounting\Services\AccountingMomentService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingMomentController extends ApiController
{
    public function __construct(
        protected AccountingMomentService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingMomentResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Momentos contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingMomentRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingMomentDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingMomentResource($item))->resolve(),
            'Accounting moment created successfully.',
            201,
        );
    }

    public function show(int $accountingMoment): JsonResponse
    {
        $item = $this->service->findOrFail($accountingMoment);

        return $this->successResponse(
            (new AccountingMomentResource($item))->resolve(),
            'Accounting moment retrieved successfully.',
        );
    }

    public function update(UpdateAccountingMomentRequest $request, AccountingMoment $accountingMoment): JsonResponse
    {
        $item = $this->service->update($accountingMoment, UpdateAccountingMomentDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingMomentResource($item))->resolve(),
            'Accounting moment updated successfully.',
        );
    }

    public function destroy(AccountingMoment $accountingMoment): JsonResponse
    {
        $this->service->delete($accountingMoment);

        return $this->successResponse(null, 'Accounting moment soft deleted successfully.');
    }

    public function restore(int $accountingMoment): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingMoment));

        return $this->successResponse(
            (new AccountingMomentResource($item))->resolve(),
            'Accounting moment restored successfully.',
        );
    }
}
