<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingNatureDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingNatureDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingNatureRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingNatureRequest;
use App\Modules\Accounting\Http\Resources\AccountingNatureResource;
use App\Modules\Accounting\Models\AccountingNature;
use App\Modules\Accounting\Services\AccountingNatureService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingNatureController extends ApiController
{
    public function __construct(
        protected AccountingNatureService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingNatureResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Naturalezas contables obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingNatureRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingNatureDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingNatureResource($item))->resolve(),
            'Accounting nature created successfully.',
            201,
        );
    }

    public function show(int $accountingNature): JsonResponse
    {
        $item = $this->service->findOrFail($accountingNature);

        return $this->successResponse(
            (new AccountingNatureResource($item))->resolve(),
            'Accounting nature retrieved successfully.',
        );
    }

    public function update(UpdateAccountingNatureRequest $request, AccountingNature $accountingNature): JsonResponse
    {
        $item = $this->service->update($accountingNature, UpdateAccountingNatureDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingNatureResource($item))->resolve(),
            'Accounting nature updated successfully.',
        );
    }

    public function destroy(AccountingNature $accountingNature): JsonResponse
    {
        $this->service->delete($accountingNature);

        return $this->successResponse(null, 'Accounting nature soft deleted successfully.');
    }

    public function restore(int $accountingNature): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingNature));

        return $this->successResponse(
            (new AccountingNatureResource($item))->resolve(),
            'Accounting nature restored successfully.',
        );
    }
}
