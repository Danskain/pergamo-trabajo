<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingAccountDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingAccountDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingAccountRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingAccountRequest;
use App\Modules\Accounting\Http\Resources\AccountingAccountResource;
use App\Modules\Accounting\Models\AccountingAccount;
use App\Modules\Accounting\Services\AccountingAccountService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingAccountController extends ApiController
{
    public function __construct(
        protected AccountingAccountService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingAccountResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Cuentas contables obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingAccountRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingAccountDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingAccountResource($item))->resolve(),
            'Accounting account created successfully.',
            201,
        );
    }

    public function show(int $accountingAccount): JsonResponse
    {
        $item = $this->service->findOrFail($accountingAccount);

        return $this->successResponse(
            (new AccountingAccountResource($item))->resolve(),
            'Accounting account retrieved successfully.',
        );
    }

    public function update(UpdateAccountingAccountRequest $request, AccountingAccount $accountingAccount): JsonResponse
    {
        $item = $this->service->update($accountingAccount, UpdateAccountingAccountDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingAccountResource($item))->resolve(),
            'Accounting account updated successfully.',
        );
    }

    public function destroy(AccountingAccount $accountingAccount): JsonResponse
    {
        $this->service->delete($accountingAccount);

        return $this->successResponse(null, 'Accounting account soft deleted successfully.');
    }

    public function restore(int $accountingAccount): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingAccount));

        return $this->successResponse(
            (new AccountingAccountResource($item))->resolve(),
            'Accounting account restored successfully.',
        );
    }
}
