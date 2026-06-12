<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateCostCenterTypeDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterTypeDTO;
use App\Modules\Accounting\Models\CostCenterType;
use App\Modules\Accounting\Repositories\Contracts\CostCenterTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCostCenterTypeRepository implements CostCenterTypeRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return CostCenterType::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): CostCenterType
    {
        return CostCenterType::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenterType
    {
        return CostCenterType::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateCostCenterTypeDTO $dto): CostCenterType
    {
        return CostCenterType::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(CostCenterType $costCenterType, UpdateCostCenterTypeDTO $dto): CostCenterType
    {
        $costCenterType->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $costCenterType->refresh();
    }

    public function delete(CostCenterType $costCenterType): void
    {
        $costCenterType->delete();
    }

    public function restore(CostCenterType $costCenterType): CostCenterType
    {
        $costCenterType->restore();

        return $costCenterType->refresh();
    }
}
