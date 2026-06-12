<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateCostCenterDTO;
use App\Modules\Accounting\DTOs\UpdateCostCenterDTO;
use App\Modules\Accounting\Models\CostCenter;
use App\Modules\Accounting\Repositories\Contracts\CostCenterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCostCenterRepository implements CostCenterRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'businessStructure',
        'campus',
        'costCenterType',
        'costCenterClass',
        'costCenterNature',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return CostCenter::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): CostCenter
    {
        return CostCenter::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): CostCenter
    {
        return CostCenter::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateCostCenterDTO $dto): CostCenter
    {
        $costCenter = CostCenter::query()->create([
            'business_structure_id' => $dto->businessStructureId,
            'campus_id' => $dto->campusId,
            'code' => $dto->code,
            'name' => $dto->name,
            'description' => $dto->description,
            'cost_center_type_id' => $dto->costCenterTypeId,
            'cost_center_class_id' => $dto->costCenterClassId,
            'cost_center_nature_id' => $dto->costCenterNatureId,
            'allows_allocation' => $dto->allowsAllocation,
            'distributes_costs' => $dto->distributesCosts,
            'functional_unit' => $dto->functionalUnit,
            'profit_center' => $dto->profitCenter,
        ]);

        return $costCenter->load($this->with);
    }

    public function update(CostCenter $costCenter, UpdateCostCenterDTO $dto): CostCenter
    {
        $costCenter->update([
            'business_structure_id' => $dto->businessStructureId,
            'campus_id' => $dto->campusId,
            'code' => $dto->code,
            'name' => $dto->name,
            'description' => $dto->description,
            'cost_center_type_id' => $dto->costCenterTypeId,
            'cost_center_class_id' => $dto->costCenterClassId,
            'cost_center_nature_id' => $dto->costCenterNatureId,
            'allows_allocation' => $dto->allowsAllocation,
            'distributes_costs' => $dto->distributesCosts,
            'functional_unit' => $dto->functionalUnit,
            'profit_center' => $dto->profitCenter,
        ]);

        return $costCenter->load($this->with);
    }

    public function delete(CostCenter $costCenter): void
    {
        $costCenter->delete();
    }

    public function restore(CostCenter $costCenter): CostCenter
    {
        $costCenter->restore();

        return $costCenter->load($this->with);
    }
}
