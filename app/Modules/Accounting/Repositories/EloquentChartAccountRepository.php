<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateChartAccountDTO;
use App\Modules\Accounting\DTOs\UpdateChartAccountDTO;
use App\Modules\Accounting\Models\ChartAccount;
use App\Modules\Accounting\Repositories\Contracts\ChartAccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentChartAccountRepository implements ChartAccountRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return ChartAccount::query()
            ->with(['accountingStandard', 'typePlan'])
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): ChartAccount
    {
        return ChartAccount::query()
            ->with(['accountingStandard', 'typePlan'])
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): ChartAccount
    {
        return ChartAccount::query()
            ->withTrashed()
            ->with(['accountingStandard', 'typePlan'])
            ->findOrFail($id);
    }

    public function create(CreateChartAccountDTO $dto): ChartAccount
    {
        $chartAccount = ChartAccount::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'accounting_standard_id' => $dto->accountingStandardId,
            'types_plan_id' => $dto->typesPlanId,
            'ceco_permission' => $dto->cecoPermission,
        ]);

        return $chartAccount->load(['accountingStandard', 'typePlan']);
    }

    public function update(ChartAccount $chartAccount, UpdateChartAccountDTO $dto): ChartAccount
    {
        $chartAccount->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'accounting_standard_id' => $dto->accountingStandardId,
            'types_plan_id' => $dto->typesPlanId,
            'ceco_permission' => $dto->cecoPermission,
        ]);

        return $chartAccount->load(['accountingStandard', 'typePlan']);
    }

    public function delete(ChartAccount $chartAccount): void
    {
        $chartAccount->delete();
    }

    public function restore(ChartAccount $chartAccount): ChartAccount
    {
        $chartAccount->restore();

        return $chartAccount->load(['accountingStandard', 'typePlan']);
    }
}
