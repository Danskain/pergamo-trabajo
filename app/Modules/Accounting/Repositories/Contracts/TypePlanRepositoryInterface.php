<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateTypePlanDTO;
use App\Modules\Accounting\DTOs\UpdateTypePlanDTO;
use App\Modules\Accounting\Models\TypePlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TypePlanRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): TypePlan;

    public function findTrashedOrFail(int $id): TypePlan;

    public function create(CreateTypePlanDTO $dto): TypePlan;

    public function update(TypePlan $typePlan, UpdateTypePlanDTO $dto): TypePlan;

    public function delete(TypePlan $typePlan): void;

    public function restore(TypePlan $typePlan): TypePlan;
}
