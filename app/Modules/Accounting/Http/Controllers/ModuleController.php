<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateModuleDTO;
use App\Modules\Accounting\DTOs\UpdateModuleDTO;
use App\Modules\Accounting\Http\Requests\StoreModuleRequest;
use App\Modules\Accounting\Http\Requests\UpdateModuleRequest;
use App\Modules\Accounting\Http\Resources\ModuleResource;
use App\Modules\Accounting\Models\Module;
use App\Modules\Accounting\Services\ModuleService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class ModuleController extends ApiController
{
    public function __construct(
        protected ModuleService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = ModuleResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Modulos obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreModuleRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateModuleDTO::fromRequest($request));

        return $this->successResponse(
            (new ModuleResource($item))->resolve(),
            'Module created successfully.',
            201,
        );
    }

    public function show(int $module): JsonResponse
    {
        $item = $this->service->findOrFail($module);

        return $this->successResponse(
            (new ModuleResource($item))->resolve(),
            'Module retrieved successfully.',
        );
    }

    public function update(UpdateModuleRequest $request, Module $module): JsonResponse
    {
        $item = $this->service->update($module, UpdateModuleDTO::fromRequest($request));

        return $this->successResponse(
            (new ModuleResource($item))->resolve(),
            'Module updated successfully.',
        );
    }

    public function destroy(Module $module): JsonResponse
    {
        $this->service->delete($module);

        return $this->successResponse(null, 'Module soft deleted successfully.');
    }

    public function restore(int $module): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($module));

        return $this->successResponse(
            (new ModuleResource($item))->resolve(),
            'Module restored successfully.',
        );
    }
}
