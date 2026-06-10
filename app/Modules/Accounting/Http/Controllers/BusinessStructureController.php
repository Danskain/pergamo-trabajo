<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateBusinessStructureDTO;
use App\Modules\Accounting\DTOs\UpdateBusinessStructureDTO;
use App\Modules\Accounting\Http\Requests\StoreBusinessStructureRequest;
use App\Modules\Accounting\Http\Requests\UpdateBusinessStructureRequest;
use App\Modules\Accounting\Http\Resources\BusinessStructureResource;
use App\Modules\Accounting\Models\BusinessStructure;
use App\Modules\Accounting\Services\BusinessStructureService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class BusinessStructureController extends ApiController
{
    public function __construct(
        protected BusinessStructureService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = BusinessStructureResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Estructuras de negocio obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreBusinessStructureRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateBusinessStructureDTO::fromRequest($request));

        return $this->successResponse(
            (new BusinessStructureResource($item))->resolve(),
            'Business structure created successfully.',
            201,
        );
    }

    public function show(int $businessStructure): JsonResponse
    {
        $item = $this->service->findOrFail($businessStructure);

        return $this->successResponse(
            (new BusinessStructureResource($item))->resolve(),
            'Business structure retrieved successfully.',
        );
    }

    public function update(UpdateBusinessStructureRequest $request, BusinessStructure $businessStructure): JsonResponse
    {
        $item = $this->service->update($businessStructure, UpdateBusinessStructureDTO::fromRequest($request));

        return $this->successResponse(
            (new BusinessStructureResource($item))->resolve(),
            'Business structure updated successfully.',
        );
    }

    public function destroy(BusinessStructure $businessStructure): JsonResponse
    {
        $this->service->delete($businessStructure);

        return $this->successResponse(null, 'Business structure soft deleted successfully.');
    }

    public function restore(int $businessStructure): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($businessStructure));

        return $this->successResponse(
            (new BusinessStructureResource($item))->resolve(),
            'Business structure restored successfully.',
        );
    }
}
