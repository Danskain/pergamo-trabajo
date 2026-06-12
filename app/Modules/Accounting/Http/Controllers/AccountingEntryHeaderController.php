<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingEntryHeaderDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryHeaderDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingEntryHeaderRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingEntryHeaderRequest;
use App\Modules\Accounting\Http\Resources\AccountingEntryHeaderResource;
use App\Modules\Accounting\Models\AccountingEntryHeader;
use App\Modules\Accounting\Services\AccountingEntryHeaderService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingEntryHeaderController extends ApiController
{
    public function __construct(
        protected AccountingEntryHeaderService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingEntryHeaderResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Encabezados contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingEntryHeaderRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingEntryHeaderDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingEntryHeaderResource($item))->resolve(),
            'Accounting entry header created successfully.',
            201,
        );
    }

    public function show(int $accountingEntryHeader): JsonResponse
    {
        $item = $this->service->findOrFail($accountingEntryHeader);

        return $this->successResponse(
            (new AccountingEntryHeaderResource($item))->resolve(),
            'Accounting entry header retrieved successfully.',
        );
    }

    public function update(UpdateAccountingEntryHeaderRequest $request, AccountingEntryHeader $accountingEntryHeader): JsonResponse
    {
        $item = $this->service->update($accountingEntryHeader, UpdateAccountingEntryHeaderDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingEntryHeaderResource($item))->resolve(),
            'Accounting entry header updated successfully.',
        );
    }

    public function destroy(AccountingEntryHeader $accountingEntryHeader): JsonResponse
    {
        $this->service->delete($accountingEntryHeader);

        return $this->successResponse(null, 'Accounting entry header soft deleted successfully.');
    }

    public function restore(int $accountingEntryHeader): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingEntryHeader));

        return $this->successResponse(
            (new AccountingEntryHeaderResource($item))->resolve(),
            'Accounting entry header restored successfully.',
        );
    }
}
