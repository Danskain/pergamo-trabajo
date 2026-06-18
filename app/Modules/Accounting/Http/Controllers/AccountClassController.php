<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountClassDTO;
use App\Modules\Accounting\DTOs\UpdateAccountClassDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountClassRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountClassRequest;
use App\Modules\Accounting\Http\Resources\AccountClassResource;
use App\Modules\Accounting\Models\AccountClass;
use App\Modules\Accounting\Services\AccountClassService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountClassController extends ApiController
{
    public function __construct(
        protected AccountClassService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountClassResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Clases contables obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountClassRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountClassDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountClassResource($item))->resolve(),
            'Account class created successfully.',
            201,
        );
    }

    public function show(int $accountClass): JsonResponse
    {
        $item = $this->service->findOrFail($accountClass);

        return $this->successResponse(
            (new AccountClassResource($item))->resolve(),
            'Account class retrieved successfully.',
        );
    }

    public function update(UpdateAccountClassRequest $request, AccountClass $accountClass): JsonResponse
    {
        $item = $this->service->update($accountClass, UpdateAccountClassDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountClassResource($item))->resolve(),
            'Account class updated successfully.',
        );
    }

    public function destroy(AccountClass $accountClass): JsonResponse
    {
        $this->service->delete($accountClass);

        return $this->successResponse(null, 'Account class soft deleted successfully.');
    }

    public function restore(int $accountClass): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountClass));

        return $this->successResponse(
            (new AccountClassResource($item))->resolve(),
            'Account class restored successfully.',
        );
    }
}
