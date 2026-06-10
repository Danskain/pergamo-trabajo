<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateChartAccountDTO;
use App\Modules\Accounting\DTOs\UpdateChartAccountDTO;
use App\Modules\Accounting\Http\Requests\StoreChartAccountRequest;
use App\Modules\Accounting\Http\Requests\UpdateChartAccountRequest;
use App\Modules\Accounting\Http\Resources\ChartAccountResource;
use App\Modules\Accounting\Models\ChartAccount;
use App\Modules\Accounting\Services\ChartAccountService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class ChartAccountController extends ApiController
{
    public function __construct(
        protected ChartAccountService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = ChartAccountResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Cuentas del plan contable obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreChartAccountRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateChartAccountDTO::fromRequest($request));

        return $this->successResponse(
            (new ChartAccountResource($item))->resolve(),
            'Chart account created successfully.',
            201,
        );
    }

    public function show(int $chartAccount): JsonResponse
    {
        $item = $this->service->findOrFail($chartAccount);

        return $this->successResponse(
            (new ChartAccountResource($item))->resolve(),
            'Chart account retrieved successfully.',
        );
    }

    public function update(UpdateChartAccountRequest $request, ChartAccount $chartAccount): JsonResponse
    {
        $item = $this->service->update($chartAccount, UpdateChartAccountDTO::fromRequest($request));

        return $this->successResponse(
            (new ChartAccountResource($item))->resolve(),
            'Chart account updated successfully.',
        );
    }

    public function destroy(ChartAccount $chartAccount): JsonResponse
    {
        $this->service->delete($chartAccount);

        return $this->successResponse(null, 'Chart account soft deleted successfully.');
    }

    public function restore(int $chartAccount): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($chartAccount));

        return $this->successResponse(
            (new ChartAccountResource($item))->resolve(),
            'Chart account restored successfully.',
        );
    }
}
