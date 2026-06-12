<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateCostCenterTypeDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterTypeDTO;
use App\Modules\Accounting\Http\Requests\StoreCostCenterTypeRequest;
use App\Modules\Accounting\Http\Requests\UpdateCostCenterTypeRequest;
use App\Modules\Accounting\Http\Resources\CostCenterTypeResource;
use App\Modules\Accounting\Models\CostCenterType;
use App\Modules\Accounting\Services\CostCenterTypeService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CostCenterTypeController extends ApiController
{
    public function __construct(
        protected CostCenterTypeService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = CostCenterTypeResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Tipos de centro de costo obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreCostCenterTypeRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateCostCenterTypeDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterTypeResource($item))->resolve(),
            'Cost center type created successfully.',
            201,
        );
    }

    public function show(int $costCenterType): JsonResponse
    {
        $item = $this->service->findOrFail($costCenterType);

        return $this->successResponse(
            (new CostCenterTypeResource($item))->resolve(),
            'Cost center type retrieved successfully.',
        );
    }

    public function update(UpdateCostCenterTypeRequest $request, CostCenterType $costCenterType): JsonResponse
    {
        $item = $this->service->update($costCenterType, UpdateCostCenterTypeDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterTypeResource($item))->resolve(),
            'Cost center type updated successfully.',
        );
    }

    public function destroy(CostCenterType $costCenterType): JsonResponse
    {
        $this->service->delete($costCenterType);

        return $this->successResponse(null, 'Cost center type soft deleted successfully.');
    }

    public function restore(int $costCenterType): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($costCenterType));

        return $this->successResponse(
            (new CostCenterTypeResource($item))->resolve(),
            'Cost center type restored successfully.',
        );
    }
}
