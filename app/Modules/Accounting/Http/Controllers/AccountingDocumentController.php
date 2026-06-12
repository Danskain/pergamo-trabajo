<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingDocumentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingDocumentDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingDocumentRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingDocumentRequest;
use App\Modules\Accounting\Http\Resources\AccountingDocumentResource;
use App\Modules\Accounting\Models\AccountingDocument;
use App\Modules\Accounting\Services\AccountingDocumentService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingDocumentController extends ApiController
{
    public function __construct(
        protected AccountingDocumentService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingDocumentResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Documentos contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingDocumentRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingDocumentDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingDocumentResource($item))->resolve(),
            'Accounting document created successfully.',
            201,
        );
    }

    public function show(int $accountingDocument): JsonResponse
    {
        $item = $this->service->findOrFail($accountingDocument);

        return $this->successResponse(
            (new AccountingDocumentResource($item))->resolve(),
            'Accounting document retrieved successfully.',
        );
    }

    public function update(UpdateAccountingDocumentRequest $request, AccountingDocument $accountingDocument): JsonResponse
    {
        $item = $this->service->update($accountingDocument, UpdateAccountingDocumentDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingDocumentResource($item))->resolve(),
            'Accounting document updated successfully.',
        );
    }

    public function destroy(AccountingDocument $accountingDocument): JsonResponse
    {
        $this->service->delete($accountingDocument);

        return $this->successResponse(null, 'Accounting document soft deleted successfully.');
    }

    public function restore(int $accountingDocument): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingDocument));

        return $this->successResponse(
            (new AccountingDocumentResource($item))->resolve(),
            'Accounting document restored successfully.',
        );
    }
}
