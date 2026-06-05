<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateExerciseVariationDTO;
use App\Modules\Accounting\DTOs\UpdateExerciseVariationDTO;
use App\Modules\Accounting\Models\ExerciseVariation;
use App\Modules\Accounting\Repositories\Contracts\ExerciseVariationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExerciseVariationService
{
    public function __construct(
        protected ExerciseVariationRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): ExerciseVariation
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): ExerciseVariation
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateExerciseVariationDTO $dto): ExerciseVariation
    {
        return $this->repository->create($dto);
    }

    public function update(ExerciseVariation $exerciseVariation, UpdateExerciseVariationDTO $dto): ExerciseVariation
    {
        return $this->repository->update($exerciseVariation, $dto);
    }

    public function delete(ExerciseVariation $exerciseVariation): void
    {
        $this->repository->delete($exerciseVariation);
    }

    public function restore(ExerciseVariation $exerciseVariation): ExerciseVariation
    {
        return $this->repository->restore($exerciseVariation);
    }
}
