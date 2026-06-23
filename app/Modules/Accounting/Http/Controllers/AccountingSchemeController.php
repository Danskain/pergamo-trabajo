<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingSchemeDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingSchemeDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingSchemeRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingSchemeRequest;
use App\Modules\Accounting\Http\Resources\AccountingSchemeResource;
use App\Modules\Accounting\Models\AccountingScheme;
use App\Modules\Accounting\Services\AccountingSchemeService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingSchemeController extends ApiController
{
    public function __construct(
        protected AccountingSchemeService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingSchemeResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Esquemas contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingSchemeRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingSchemeDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingSchemeResource($item))->resolve(),
            'Accounting scheme created successfully.',
            201,
        );
    }

    public function show(int $accountingScheme): JsonResponse
    {
        $item = $this->service->findOrFail($accountingScheme);

        return $this->successResponse(
            (new AccountingSchemeResource($item))->resolve(),
            'Accounting scheme retrieved successfully.',
        );
    }

    public function update(UpdateAccountingSchemeRequest $request, AccountingScheme $accountingScheme): JsonResponse
    {
        $item = $this->service->update($accountingScheme, UpdateAccountingSchemeDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingSchemeResource($item))->resolve(),
            'Accounting scheme updated successfully.',
        );
    }

    public function destroy(AccountingScheme $accountingScheme): JsonResponse
    {
        $this->service->delete($accountingScheme);

        return $this->successResponse(null, 'Accounting scheme soft deleted successfully.');
    }

    public function restore(int $accountingScheme): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingScheme));

        return $this->successResponse(
            (new AccountingSchemeResource($item))->resolve(),
            'Accounting scheme restored successfully.',
        );
    }
}
