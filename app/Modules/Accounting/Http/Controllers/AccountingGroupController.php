<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingGroupDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingGroupDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingGroupRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingGroupRequest;
use App\Modules\Accounting\Http\Resources\AccountingGroupResource;
use App\Modules\Accounting\Models\AccountingGroup;
use App\Modules\Accounting\Services\AccountingGroupService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingGroupController extends ApiController
{
    public function __construct(
        protected AccountingGroupService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingGroupResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Grupos contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingGroupRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingGroupDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingGroupResource($item))->resolve(),
            'Accounting group created successfully.',
            201,
        );
    }

    public function show(int $accountingGroup): JsonResponse
    {
        $item = $this->service->findOrFail($accountingGroup);

        return $this->successResponse(
            (new AccountingGroupResource($item))->resolve(),
            'Accounting group retrieved successfully.',
        );
    }

    public function update(UpdateAccountingGroupRequest $request, AccountingGroup $accountingGroup): JsonResponse
    {
        $item = $this->service->update($accountingGroup, UpdateAccountingGroupDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingGroupResource($item))->resolve(),
            'Accounting group updated successfully.',
        );
    }

    public function destroy(AccountingGroup $accountingGroup): JsonResponse
    {
        $this->service->delete($accountingGroup);

        return $this->successResponse(null, 'Accounting group soft deleted successfully.');
    }

    public function restore(int $accountingGroup): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingGroup));

        return $this->successResponse(
            (new AccountingGroupResource($item))->resolve(),
            'Accounting group restored successfully.',
        );
    }
}
