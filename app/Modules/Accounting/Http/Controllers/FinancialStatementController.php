<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateFinancialStatementDTO;
use App\Modules\Accounting\DTOs\UpdateFinancialStatementDTO;
use App\Modules\Accounting\Http\Requests\StoreFinancialStatementRequest;
use App\Modules\Accounting\Http\Requests\UpdateFinancialStatementRequest;
use App\Modules\Accounting\Http\Resources\FinancialStatementResource;
use App\Modules\Accounting\Models\FinancialStatement;
use App\Modules\Accounting\Services\FinancialStatementService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class FinancialStatementController extends ApiController
{
    public function __construct(
        protected FinancialStatementService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = FinancialStatementResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Estados financieros obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreFinancialStatementRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateFinancialStatementDTO::fromRequest($request));

        return $this->successResponse(
            (new FinancialStatementResource($item))->resolve(),
            'Financial statement created successfully.',
            201,
        );
    }

    public function show(int $financialStatement): JsonResponse
    {
        $item = $this->service->findOrFail($financialStatement);

        return $this->successResponse(
            (new FinancialStatementResource($item))->resolve(),
            'Financial statement retrieved successfully.',
        );
    }

    public function update(UpdateFinancialStatementRequest $request, FinancialStatement $financialStatement): JsonResponse
    {
        $item = $this->service->update($financialStatement, UpdateFinancialStatementDTO::fromRequest($request));

        return $this->successResponse(
            (new FinancialStatementResource($item))->resolve(),
            'Financial statement updated successfully.',
        );
    }

    public function destroy(FinancialStatement $financialStatement): JsonResponse
    {
        $this->service->delete($financialStatement);

        return $this->successResponse(null, 'Financial statement soft deleted successfully.');
    }

    public function restore(int $financialStatement): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($financialStatement));

        return $this->successResponse(
            (new FinancialStatementResource($item))->resolve(),
            'Financial statement restored successfully.',
        );
    }
}
