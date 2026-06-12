<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateCostCenterClassDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterClassDTO;
use App\Modules\Accounting\Models\CostCenterClass;
use App\Modules\Accounting\Repositories\Contracts\CostCenterClassRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCostCenterClassRepository implements CostCenterClassRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return CostCenterClass::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): CostCenterClass
    {
        return CostCenterClass::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenterClass
    {
        return CostCenterClass::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateCostCenterClassDTO $dto): CostCenterClass
    {
        return CostCenterClass::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(CostCenterClass $costCenterClass, UpdateCostCenterClassDTO $dto): CostCenterClass
    {
        $costCenterClass->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $costCenterClass->refresh();
    }

    public function delete(CostCenterClass $costCenterClass): void
    {
        $costCenterClass->delete();
    }

    public function restore(CostCenterClass $costCenterClass): CostCenterClass
    {
        $costCenterClass->restore();

        return $costCenterClass->refresh();
    }
}
