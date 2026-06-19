<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateKeyOperationDTO;
use App\Modules\Accounting\DTOs\UpdateKeyOperationDTO;
use App\Modules\Accounting\Http\Requests\StoreKeyOperationRequest;
use App\Modules\Accounting\Http\Requests\UpdateKeyOperationRequest;
use App\Modules\Accounting\Http\Resources\KeyOperationResource;
use App\Modules\Accounting\Models\KeyOperation;
use App\Modules\Accounting\Services\KeyOperationService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class KeyOperationController extends ApiController
{
    public function __construct(
        protected KeyOperationService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = KeyOperationResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Operaciones clave obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreKeyOperationRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateKeyOperationDTO::fromRequest($request));

        return $this->successResponse(
            (new KeyOperationResource($item))->resolve(),
            'Key operation created successfully.',
            201,
        );
    }

    public function show(int $keyOperation): JsonResponse
    {
        $item = $this->service->findOrFail($keyOperation);

        return $this->successResponse(
            (new KeyOperationResource($item))->resolve(),
            'Key operation retrieved successfully.',
        );
    }

    public function update(UpdateKeyOperationRequest $request, KeyOperation $keyOperation): JsonResponse
    {
        $item = $this->service->update($keyOperation, UpdateKeyOperationDTO::fromRequest($request));

        return $this->successResponse(
            (new KeyOperationResource($item))->resolve(),
            'Key operation updated successfully.',
        );
    }

    public function destroy(KeyOperation $keyOperation): JsonResponse
    {
        $this->service->delete($keyOperation);

        return $this->successResponse(null, 'Key operation soft deleted successfully.');
    }

    public function restore(int $keyOperation): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($keyOperation));

        return $this->successResponse(
            (new KeyOperationResource($item))->resolve(),
            'Key operation restored successfully.',
        );
    }
}
