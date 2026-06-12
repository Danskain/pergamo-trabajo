<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateCostCenterTypeDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterTypeDTO;
use App\Modules\Accounting\Models\CostCenterType;
use App\Modules\Accounting\Repositories\Contracts\CostCenterTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CostCenterTypeService
{
    public function __construct(
        protected CostCenterTypeRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): CostCenterType
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenterType
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateCostCenterTypeDTO $dto): CostCenterType
    {
        return $this->repository->create($dto);
    }

    public function update(CostCenterType $costCenterType, UpdateCostCenterTypeDTO $dto): CostCenterType
    {
        return $this->repository->update($costCenterType, $dto);
    }

    public function delete(CostCenterType $costCenterType): void
    {
        $this->repository->delete($costCenterType);
    }

    public function restore(CostCenterType $costCenterType): CostCenterType
    {
        return $this->repository->restore($costCenterType);
    }
}
