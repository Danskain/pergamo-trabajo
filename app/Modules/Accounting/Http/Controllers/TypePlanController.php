<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateTypePlanDTO;
use App\Modules\Accounting\DTOs\UpdateTypePlanDTO;
use App\Modules\Accounting\Http\Requests\StoreTypePlanRequest;
use App\Modules\Accounting\Http\Requests\UpdateTypePlanRequest;
use App\Modules\Accounting\Http\Resources\TypePlanResource;
use App\Modules\Accounting\Models\TypePlan;
use App\Modules\Accounting\Services\TypePlanService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class TypePlanController extends ApiController
{
    public function __construct(
        protected TypePlanService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = TypePlanResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Tipos de plan obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreTypePlanRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateTypePlanDTO::fromRequest($request));

        return $this->successResponse(
            (new TypePlanResource($item))->resolve(),
            'Type plan created successfully.',
            201,
        );
    }

    public function show(int $typePlan): JsonResponse
    {
        $item = $this->service->findOrFail($typePlan);

        return $this->successResponse(
            (new TypePlanResource($item))->resolve(),
            'Type plan retrieved successfully.',
        );
    }

    public function update(UpdateTypePlanRequest $request, TypePlan $typePlan): JsonResponse
    {
        $item = $this->service->update($typePlan, UpdateTypePlanDTO::fromRequest($request));

        return $this->successResponse(
            (new TypePlanResource($item))->resolve(),
            'Type plan updated successfully.',
        );
    }

    public function destroy(TypePlan $typePlan): JsonResponse
    {
        $this->service->delete($typePlan);

        return $this->successResponse(null, 'Type plan soft deleted successfully.');
    }

    public function restore(int $typePlan): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($typePlan));

        return $this->successResponse(
            (new TypePlanResource($item))->resolve(),
            'Type plan restored successfully.',
        );
    }
}
