<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateTypePlanDTO;
use App\Modules\Accounting\DTOs\UpdateTypePlanDTO;
use App\Modules\Accounting\Models\TypePlan;
use App\Modules\Accounting\Repositories\Contracts\TypePlanRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTypePlanRepository implements TypePlanRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return TypePlan::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): TypePlan
    {
        return TypePlan::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): TypePlan
    {
        return TypePlan::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateTypePlanDTO $dto): TypePlan
    {
        return TypePlan::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(TypePlan $typePlan, UpdateTypePlanDTO $dto): TypePlan
    {
        $typePlan->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $typePlan->refresh();
    }

    public function delete(TypePlan $typePlan): void
    {
        $typePlan->delete();
    }

    public function restore(TypePlan $typePlan): TypePlan
    {
        $typePlan->restore();

        return $typePlan->refresh();
    }
}
