<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateCostCenterClassDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterClassDTO;
use App\Modules\Accounting\Models\CostCenterClass;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CostCenterClassRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): CostCenterClass;

    public function findTrashedOrFail(int $id): CostCenterClass;

    public function create(CreateCostCenterClassDTO $dto): CostCenterClass;

    public function update(CostCenterClass $costCenterClass, UpdateCostCenterClassDTO $dto): CostCenterClass;

    public function delete(CostCenterClass $costCenterClass): void;

    public function restore(CostCenterClass $costCenterClass): CostCenterClass;
}
