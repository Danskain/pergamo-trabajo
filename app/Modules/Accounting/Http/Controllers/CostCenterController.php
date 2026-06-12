<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateCostCenterDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterDTO;
use App\Modules\Accounting\Http\Requests\StoreCostCenterRequest;
use App\Modules\Accounting\Http\Requests\UpdateCostCenterRequest;
use App\Modules\Accounting\Http\Resources\CostCenterResource;
use App\Modules\Accounting\Models\CostCenter;
use App\Modules\Accounting\Services\CostCenterService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CostCenterController extends ApiController
{
    public function __construct(
        protected CostCenterService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = CostCenterResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Centros de costo obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreCostCenterRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateCostCenterDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterResource($item))->resolve(),
            'Cost center created successfully.',
            201,
        );
    }

    public function show(int $costCenter): JsonResponse
    {
        $item = $this->service->findOrFail($costCenter);

        return $this->successResponse(
            (new CostCenterResource($item))->resolve(),
            'Cost center retrieved successfully.',
        );
    }

    public function update(UpdateCostCenterRequest $request, CostCenter $costCenter): JsonResponse
    {
        $item = $this->service->update($costCenter, UpdateCostCenterDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterResource($item))->resolve(),
            'Cost center updated successfully.',
        );
    }

    public function destroy(CostCenter $costCenter): JsonResponse
    {
        $this->service->delete($costCenter);

        return $this->successResponse(null, 'Cost center soft deleted successfully.');
    }

    public function restore(int $costCenter): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($costCenter));

        return $this->successResponse(
            (new CostCenterResource($item))->resolve(),
            'Cost center restored successfully.',
        );
    }
}
