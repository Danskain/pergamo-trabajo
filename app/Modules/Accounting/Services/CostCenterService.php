<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateCostCenterDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterDTO;
use App\Modules\Accounting\Models\CostCenter;
use App\Modules\Accounting\Repositories\Contracts\CostCenterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CostCenterService
{
    public function __construct(
        protected CostCenterRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): CostCenter
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenter
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateCostCenterDTO $dto): CostCenter
    {
        return $this->repository->create($dto);
    }

    public function update(CostCenter $costCenter, UpdateCostCenterDTO $dto): CostCenter
    {
        return $this->repository->update($costCenter, $dto);
    }

    public function delete(CostCenter $costCenter): void
    {
        $this->repository->delete($costCenter);
    }

    public function restore(CostCenter $costCenter): CostCenter
    {
        return $this->repository->restore($costCenter);
    }
}
