<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateCostCenterClassDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterClassDTO;
use App\Modules\Accounting\Http\Requests\StoreCostCenterClassRequest;
use App\Modules\Accounting\Http\Requests\UpdateCostCenterClassRequest;
use App\Modules\Accounting\Http\Resources\CostCenterClassResource;
use App\Modules\Accounting\Models\CostCenterClass;
use App\Modules\Accounting\Services\CostCenterClassService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CostCenterClassController extends ApiController
{
    public function __construct(
        protected CostCenterClassService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = CostCenterClassResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Clases de centro de costo obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreCostCenterClassRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateCostCenterClassDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterClassResource($item))->resolve(),
            'Cost center class created successfully.',
            201,
        );
    }

    public function show(int $costCenterClass): JsonResponse
    {
        $item = $this->service->findOrFail($costCenterClass);

        return $this->successResponse(
            (new CostCenterClassResource($item))->resolve(),
            'Cost center class retrieved successfully.',
        );
    }

    public function update(UpdateCostCenterClassRequest $request, CostCenterClass $costCenterClass): JsonResponse
    {
        $item = $this->service->update($costCenterClass, UpdateCostCenterClassDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterClassResource($item))->resolve(),
            'Cost center class updated successfully.',
        );
    }

    public function destroy(CostCenterClass $costCenterClass): JsonResponse
    {
        $this->service->delete($costCenterClass);

        return $this->successResponse(null, 'Cost center class soft deleted successfully.');
    }

    public function restore(int $costCenterClass): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($costCenterClass));

        return $this->successResponse(
            (new CostCenterClassResource($item))->resolve(),
            'Cost center class restored successfully.',
        );
    }
}
