<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateDocumentSourceTypeDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceTypeDTO;
use App\Modules\Accounting\Http\Requests\StoreDocumentSourceTypeRequest;
use App\Modules\Accounting\Http\Requests\UpdateDocumentSourceTypeRequest;
use App\Modules\Accounting\Http\Resources\DocumentSourceTypeResource;
use App\Modules\Accounting\Models\DocumentSourceType;
use App\Modules\Accounting\Services\DocumentSourceTypeService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class DocumentSourceTypeController extends ApiController
{
    public function __construct(
        protected DocumentSourceTypeService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = DocumentSourceTypeResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Tipos de origen de documento obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreDocumentSourceTypeRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateDocumentSourceTypeDTO::fromRequest($request));

        return $this->successResponse(
            (new DocumentSourceTypeResource($item))->resolve(),
            'Document source type created successfully.',
            201,
        );
    }

    public function show(int $documentSourceType): JsonResponse
    {
        $item = $this->service->findOrFail($documentSourceType);

        return $this->successResponse(
            (new DocumentSourceTypeResource($item))->resolve(),
            'Document source type retrieved successfully.',
        );
    }

    public function update(UpdateDocumentSourceTypeRequest $request, DocumentSourceType $documentSourceType): JsonResponse
    {
        $item = $this->service->update($documentSourceType, UpdateDocumentSourceTypeDTO::fromRequest($request));

        return $this->successResponse(
            (new DocumentSourceTypeResource($item))->resolve(),
            'Document source type updated successfully.',
        );
    }

    public function destroy(DocumentSourceType $documentSourceType): JsonResponse
    {
        $this->service->delete($documentSourceType);

        return $this->successResponse(null, 'Document source type soft deleted successfully.');
    }

    public function restore(int $documentSourceType): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($documentSourceType));

        return $this->successResponse(
            (new DocumentSourceTypeResource($item))->resolve(),
            'Document source type restored successfully.',
        );
    }
}
