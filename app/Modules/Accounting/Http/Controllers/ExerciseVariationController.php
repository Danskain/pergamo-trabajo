<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateExerciseVariationDTO;
use App\Modules\Accounting\DTOs\UpdateExerciseVariationDTO;
use App\Modules\Accounting\Http\Requests\StoreExerciseVariationRequest;
use App\Modules\Accounting\Http\Requests\UpdateExerciseVariationRequest;
use App\Modules\Accounting\Http\Resources\ExerciseVariationResource;
use App\Modules\Accounting\Models\ExerciseVariation;
use App\Modules\Accounting\Services\ExerciseVariationService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class ExerciseVariationController extends ApiController
{
    public function __construct(
        protected ExerciseVariationService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = ExerciseVariationResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Variaciones de ejercicio obtenidas exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreExerciseVariationRequest $request): JsonResponse
    {
        $exerciseVariation = $this->service->create(CreateExerciseVariationDTO::fromRequest($request));

        return $this->successResponse(
            (new ExerciseVariationResource($exerciseVariation))->resolve(),
            'Exercise variation created successfully.',
            201,
        );
    }

    public function show(int $exerciseVariation): JsonResponse
    {
        $item = $this->service->findOrFail($exerciseVariation);

        return $this->successResponse(
            (new ExerciseVariationResource($item))->resolve(),
            'Exercise variation retrieved successfully.',
        );
    }

    public function update(UpdateExerciseVariationRequest $request, ExerciseVariation $exerciseVariation): JsonResponse
    {
        $item = $this->service->update($exerciseVariation, UpdateExerciseVariationDTO::fromRequest($request));

        return $this->successResponse(
            (new ExerciseVariationResource($item))->resolve(),
            'Exercise variation updated successfully.',
        );
    }

    public function destroy(ExerciseVariation $exerciseVariation): JsonResponse
    {
        $this->service->delete($exerciseVariation);

        return $this->successResponse(null, 'Exercise variation soft deleted successfully.');
    }

    public function restore(int $exerciseVariation): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($exerciseVariation));

        return $this->successResponse(
            (new ExerciseVariationResource($item))->resolve(),
            'Exercise variation restored successfully.',
        );
    }
}
