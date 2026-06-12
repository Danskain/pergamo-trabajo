<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateCostCenterNatureDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterNatureDTO;
use App\Modules\Accounting\Http\Requests\StoreCostCenterNatureRequest;
use App\Modules\Accounting\Http\Requests\UpdateCostCenterNatureRequest;
use App\Modules\Accounting\Http\Resources\CostCenterNatureResource;
use App\Modules\Accounting\Models\CostCenterNature;
use App\Modules\Accounting\Services\CostCenterNatureService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CostCenterNatureController extends ApiController
{
    public function __construct(
        protected CostCenterNatureService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = CostCenterNatureResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Naturalezas de centro de costo obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreCostCenterNatureRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateCostCenterNatureDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterNatureResource($item))->resolve(),
            'Cost center nature created successfully.',
            201,
        );
    }

    public function show(int $costCenterNature): JsonResponse
    {
        $item = $this->service->findOrFail($costCenterNature);

        return $this->successResponse(
            (new CostCenterNatureResource($item))->resolve(),
            'Cost center nature retrieved successfully.',
        );
    }

    public function update(UpdateCostCenterNatureRequest $request, CostCenterNature $costCenterNature): JsonResponse
    {
        $item = $this->service->update($costCenterNature, UpdateCostCenterNatureDTO::fromRequest($request));

        return $this->successResponse(
            (new CostCenterNatureResource($item))->resolve(),
            'Cost center nature updated successfully.',
        );
    }

    public function destroy(CostCenterNature $costCenterNature): JsonResponse
    {
        $this->service->delete($costCenterNature);

        return $this->successResponse(null, 'Cost center nature soft deleted successfully.');
    }

    public function restore(int $costCenterNature): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($costCenterNature));

        return $this->successResponse(
            (new CostCenterNatureResource($item))->resolve(),
            'Cost center nature restored successfully.',
        );
    }
}
