<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingStandardDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingStandardDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingStandardRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingStandardRequest;
use App\Modules\Accounting\Http\Resources\AccountingStandardResource;
use App\Modules\Accounting\Models\AccountingStandard;
use App\Modules\Accounting\Services\AccountingStandardService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingStandardController extends ApiController
{
    public function __construct(
        protected AccountingStandardService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingStandardResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Estandares contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingStandardRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingStandardDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingStandardResource($item))->resolve(),
            'Accounting standard created successfully.',
            201,
        );
    }

    public function show(int $accountingStandard): JsonResponse
    {
        $item = $this->service->findOrFail($accountingStandard);

        return $this->successResponse(
            (new AccountingStandardResource($item))->resolve(),
            'Accounting standard retrieved successfully.',
        );
    }

    public function update(UpdateAccountingStandardRequest $request, AccountingStandard $accountingStandard): JsonResponse
    {
        $item = $this->service->update($accountingStandard, UpdateAccountingStandardDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingStandardResource($item))->resolve(),
            'Accounting standard updated successfully.',
        );
    }

    public function destroy(AccountingStandard $accountingStandard): JsonResponse
    {
        $this->service->delete($accountingStandard);

        return $this->successResponse(null, 'Accounting standard soft deleted successfully.');
    }

    public function restore(int $accountingStandard): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingStandard));

        return $this->successResponse(
            (new AccountingStandardResource($item))->resolve(),
            'Accounting standard restored successfully.',
        );
    }
}
