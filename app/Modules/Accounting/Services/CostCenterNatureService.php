<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateCostCenterNatureDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterNatureDTO;
use App\Modules\Accounting\Models\CostCenterNature;
use App\Modules\Accounting\Repositories\Contracts\CostCenterNatureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CostCenterNatureService
{
    public function __construct(
        protected CostCenterNatureRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): CostCenterNature
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenterNature
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateCostCenterNatureDTO $dto): CostCenterNature
    {
        return $this->repository->create($dto);
    }

    public function update(CostCenterNature $costCenterNature, UpdateCostCenterNatureDTO $dto): CostCenterNature
    {
        return $this->repository->update($costCenterNature, $dto);
    }

    public function delete(CostCenterNature $costCenterNature): void
    {
        $this->repository->delete($costCenterNature);
    }

    public function restore(CostCenterNature $costCenterNature): CostCenterNature
    {
        return $this->repository->restore($costCenterNature);
    }
}
