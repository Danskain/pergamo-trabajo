<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateExerciseVariationDTO;
use App\Modules\Accounting\DTOs\UpdateExerciseVariationDTO;
use App\Modules\Accounting\Models\ExerciseVariation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExerciseVariationRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): ExerciseVariation;

    public function findTrashedOrFail(int $id): ExerciseVariation;

    public function create(CreateExerciseVariationDTO $dto): ExerciseVariation;

    public function update(ExerciseVariation $exerciseVariation, UpdateExerciseVariationDTO $dto): ExerciseVariation;

    public function delete(ExerciseVariation $exerciseVariation): void;

    public function restore(ExerciseVariation $exerciseVariation): ExerciseVariation;
}
