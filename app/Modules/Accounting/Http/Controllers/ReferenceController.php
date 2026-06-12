<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateReferenceDTO;
use App\Modules\Accounting\DTOs\UpdateReferenceDTO;
use App\Modules\Accounting\Http\Requests\StoreReferenceRequest;
use App\Modules\Accounting\Http\Requests\UpdateReferenceRequest;
use App\Modules\Accounting\Http\Resources\ReferenceResource;
use App\Modules\Accounting\Models\Reference;
use App\Modules\Accounting\Services\ReferenceService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class ReferenceController extends ApiController
{
    public function __construct(
        protected ReferenceService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = ReferenceResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Referencias obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreReferenceRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateReferenceDTO::fromRequest($request));

        return $this->successResponse(
            (new ReferenceResource($item))->resolve(),
            'Reference created successfully.',
            201,
        );
    }

    public function show(int $reference): JsonResponse
    {
        $item = $this->service->findOrFail($reference);

        return $this->successResponse(
            (new ReferenceResource($item))->resolve(),
            'Reference retrieved successfully.',
        );
    }

    public function update(UpdateReferenceRequest $request, Reference $reference): JsonResponse
    {
        $item = $this->service->update($reference, UpdateReferenceDTO::fromRequest($request));

        return $this->successResponse(
            (new ReferenceResource($item))->resolve(),
            'Reference updated successfully.',
        );
    }

    public function destroy(Reference $reference): JsonResponse
    {
        $this->service->delete($reference);

        return $this->successResponse(null, 'Reference soft deleted successfully.');
    }

    public function restore(int $reference): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($reference));

        return $this->successResponse(
            (new ReferenceResource($item))->resolve(),
            'Reference restored successfully.',
        );
    }
}
