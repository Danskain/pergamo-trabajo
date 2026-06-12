<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateCostCenterDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterDTO;
use App\Modules\Accounting\Models\CostCenter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CostCenterRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): CostCenter;

    public function findTrashedOrFail(int $id): CostCenter;

    public function create(CreateCostCenterDTO $dto): CostCenter;

    public function update(CostCenter $costCenter, UpdateCostCenterDTO $dto): CostCenter;

    public function delete(CostCenter $costCenter): void;

    public function restore(CostCenter $costCenter): CostCenter;
}
