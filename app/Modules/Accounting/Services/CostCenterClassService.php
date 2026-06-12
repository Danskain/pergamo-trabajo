<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateCostCenterClassDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterClassDTO;
use App\Modules\Accounting\Models\CostCenterClass;
use App\Modules\Accounting\Repositories\Contracts\CostCenterClassRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CostCenterClassService
{
    public function __construct(
        protected CostCenterClassRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): CostCenterClass
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenterClass
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateCostCenterClassDTO $dto): CostCenterClass
    {
        return $this->repository->create($dto);
    }

    public function update(CostCenterClass $costCenterClass, UpdateCostCenterClassDTO $dto): CostCenterClass
    {
        return $this->repository->update($costCenterClass, $dto);
    }

    public function delete(CostCenterClass $costCenterClass): void
    {
        $this->repository->delete($costCenterClass);
    }

    public function restore(CostCenterClass $costCenterClass): CostCenterClass
    {
        return $this->repository->restore($costCenterClass);
    }
}
