<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateCostCenterNatureDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterNatureDTO;
use App\Modules\Accounting\Models\CostCenterNature;
use App\Modules\Accounting\Repositories\Contracts\CostCenterNatureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCostCenterNatureRepository implements CostCenterNatureRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return CostCenterNature::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): CostCenterNature
    {
        return CostCenterNature::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenterNature
    {
        return CostCenterNature::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateCostCenterNatureDTO $dto): CostCenterNature
    {
        return CostCenterNature::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(CostCenterNature $costCenterNature, UpdateCostCenterNatureDTO $dto): CostCenterNature
    {
        $costCenterNature->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $costCenterNature->refresh();
    }

    public function delete(CostCenterNature $costCenterNature): void
    {
        $costCenterNature->delete();
    }

    public function restore(CostCenterNature $costCenterNature): CostCenterNature
    {
        $costCenterNature->restore();

        return $costCenterNature->refresh();
    }
}
