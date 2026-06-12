<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateDocumentSourceDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceDTO;
use App\Modules\Accounting\Http\Requests\StoreDocumentSourceRequest;
use App\Modules\Accounting\Http\Requests\UpdateDocumentSourceRequest;
use App\Modules\Accounting\Http\Resources\DocumentSourceResource;
use App\Modules\Accounting\Models\DocumentSource;
use App\Modules\Accounting\Services\DocumentSourceService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class DocumentSourceController extends ApiController
{
    public function __construct(
        protected DocumentSourceService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = DocumentSourceResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Fuentes de documentos obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreDocumentSourceRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateDocumentSourceDTO::fromRequest($request));

        return $this->successResponse(
            (new DocumentSourceResource($item))->resolve(),
            'Document source created successfully.',
            201,
        );
    }

    public function show(int $documentSource): JsonResponse
    {
        $item = $this->service->findOrFail($documentSource);

        return $this->successResponse(
            (new DocumentSourceResource($item))->resolve(),
            'Document source retrieved successfully.',
        );
    }

    public function update(UpdateDocumentSourceRequest $request, DocumentSource $documentSource): JsonResponse
    {
        $item = $this->service->update($documentSource, UpdateDocumentSourceDTO::fromRequest($request));

        return $this->successResponse(
            (new DocumentSourceResource($item))->resolve(),
            'Document source updated successfully.',
        );
    }

    public function destroy(DocumentSource $documentSource): JsonResponse
    {
        $this->service->delete($documentSource);

        return $this->successResponse(null, 'Document source soft deleted successfully.');
    }

    public function restore(int $documentSource): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($documentSource));

        return $this->successResponse(
            (new DocumentSourceResource($item))->resolve(),
            'Document source restored successfully.',
        );
    }
}
