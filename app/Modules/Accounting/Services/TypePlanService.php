<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateTypePlanDTO;
use App\Modules\Accounting\DTOs\UpdateTypePlanDTO;
use App\Modules\Accounting\Models\TypePlan;
use App\Modules\Accounting\Repositories\Contracts\TypePlanRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TypePlanService
{
    public function __construct(
        protected TypePlanRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): TypePlan
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): TypePlan
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateTypePlanDTO $dto): TypePlan
    {
        return $this->repository->create($dto);
    }

    public function update(TypePlan $typePlan, UpdateTypePlanDTO $dto): TypePlan
    {
        return $this->repository->update($typePlan, $dto);
    }

    public function delete(TypePlan $typePlan): void
    {
        $this->repository->delete($typePlan);
    }

    public function restore(TypePlan $typePlan): TypePlan
    {
        return $this->repository->restore($typePlan);
    }
}
