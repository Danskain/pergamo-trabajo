<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateTypeAccountDTO;
use App\Modules\Accounting\DTOs\UpdateTypeAccountDTO;
use App\Modules\Accounting\Http\Requests\StoreTypeAccountRequest;
use App\Modules\Accounting\Http\Requests\UpdateTypeAccountRequest;
use App\Modules\Accounting\Http\Resources\TypeAccountResource;
use App\Modules\Accounting\Models\TypeAccount;
use App\Modules\Accounting\Services\TypeAccountService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class TypeAccountController extends ApiController
{
    public function __construct(
        protected TypeAccountService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = TypeAccountResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Tipos de cuenta obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreTypeAccountRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateTypeAccountDTO::fromRequest($request));

        return $this->successResponse(
            (new TypeAccountResource($item))->resolve(),
            'Type account created successfully.',
            201,
        );
    }

    public function show(int $typeAccount): JsonResponse
    {
        $item = $this->service->findOrFail($typeAccount);

        return $this->successResponse(
            (new TypeAccountResource($item))->resolve(),
            'Type account retrieved successfully.',
        );
    }

    public function update(UpdateTypeAccountRequest $request, TypeAccount $typeAccount): JsonResponse
    {
        $item = $this->service->update($typeAccount, UpdateTypeAccountDTO::fromRequest($request));

        return $this->successResponse(
            (new TypeAccountResource($item))->resolve(),
            'Type account updated successfully.',
        );
    }

    public function destroy(TypeAccount $typeAccount): JsonResponse
    {
        $this->service->delete($typeAccount);

        return $this->successResponse(null, 'Type account soft deleted successfully.');
    }

    public function restore(int $typeAccount): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($typeAccount));

        return $this->successResponse(
            (new TypeAccountResource($item))->resolve(),
            'Type account restored successfully.',
        );
    }
}
