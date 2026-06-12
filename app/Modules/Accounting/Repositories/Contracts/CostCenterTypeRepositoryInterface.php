<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateCostCenterTypeDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterTypeDTO;
use App\Modules\Accounting\Models\CostCenterType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CostCenterTypeRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): CostCenterType;

    public function findTrashedOrFail(int $id): CostCenterType;

    public function create(CreateCostCenterTypeDTO $dto): CostCenterType;

    public function update(CostCenterType $costCenterType, UpdateCostCenterTypeDTO $dto): CostCenterType;

    public function delete(CostCenterType $costCenterType): void;

    public function restore(CostCenterType $costCenterType): CostCenterType;
}
