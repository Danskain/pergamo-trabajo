<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateExerciseVariationDTO;
use App\Modules\Accounting\DTOs\UpdateExerciseVariationDTO;
use App\Modules\Accounting\Models\ExerciseVariation;
use App\Modules\Accounting\Repositories\Contracts\ExerciseVariationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentExerciseVariationRepository implements ExerciseVariationRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return ExerciseVariation::query()
            ->with(['startMonth', 'endMonth'])
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): ExerciseVariation
    {
        return ExerciseVariation::query()
            ->with(['startMonth', 'endMonth'])
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): ExerciseVariation
    {
        return ExerciseVariation::query()
            ->withTrashed()
            ->with(['startMonth', 'endMonth'])
            ->findOrFail($id);
    }

    public function create(CreateExerciseVariationDTO $dto): ExerciseVariation
    {
        $exerciseVariation = ExerciseVariation::query()->create([
            'code' => $dto->code,
            'name' => $dto->name,
            'start_exercise' => $dto->startExercise,
            'end_exercise' => $dto->endExercise,
            'normal_periods' => $dto->normalPeriods,
            'special_periods' => $dto->specialPeriods,
            'calendar_dependent' => $dto->calendarDependent,
            'description' => $dto->description,
        ]);

        return $exerciseVariation->load(['startMonth', 'endMonth']);
    }

    public function update(ExerciseVariation $exerciseVariation, UpdateExerciseVariationDTO $dto): ExerciseVariation
    {
        $exerciseVariation->update([
            'code' => $dto->code,
            'name' => $dto->name,
            'start_exercise' => $dto->startExercise,
            'end_exercise' => $dto->endExercise,
            'normal_periods' => $dto->normalPeriods,
            'special_periods' => $dto->specialPeriods,
            'calendar_dependent' => $dto->calendarDependent,
            'description' => $dto->description,
        ]);

        return $exerciseVariation->load(['startMonth', 'endMonth']);
    }

    public function delete(ExerciseVariation $exerciseVariation): void
    {
        $exerciseVariation->delete();
    }

    public function restore(ExerciseVariation $exerciseVariation): ExerciseVariation
    {
        $exerciseVariation->restore();

        return $exerciseVariation->load(['startMonth', 'endMonth']);
    }
}
