<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateCostCenterNatureDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterNatureDTO;
use App\Modules\Accounting\Models\CostCenterNature;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CostCenterNatureRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): CostCenterNature;

    public function findTrashedOrFail(int $id): CostCenterNature;

    public function create(CreateCostCenterNatureDTO $dto): CostCenterNature;

    public function update(CostCenterNature $costCenterNature, UpdateCostCenterNatureDTO $dto): CostCenterNature;

    public function delete(CostCenterNature $costCenterNature): void;

    public function restore(CostCenterNature $costCenterNature): CostCenterNature;
}
