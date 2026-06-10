<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingGroupDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingGroupDTO;
use App\Modules\Accounting\Models\AccountingGroup;
use App\Modules\Accounting\Repositories\Contracts\AccountingGroupRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingGroupRepository implements AccountingGroupRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingGroup::query()
            ->with(['accountClass.accountingNature'])
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingGroup
    {
        return AccountingGroup::query()
            ->with(['accountClass.accountingNature'])
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingGroup
    {
        return AccountingGroup::query()
            ->withTrashed()
            ->with(['accountClass.accountingNature'])
            ->findOrFail($id);
    }

    public function create(CreateAccountingGroupDTO $dto): AccountingGroup
    {
        $accountingGroup = AccountingGroup::query()->create([
            'code' => $dto->code,
            'account_class_id' => $dto->accountClassId,
            'name' => $dto->name,
            'description' => $dto->description,
            'account_from' => $dto->accountFrom,
            'account_to' => $dto->accountTo,
            'affects_closing' => $dto->affectsClosing,
            'affects_financial_statements' => $dto->affectsFinancialStatements,
        ]);

        return $accountingGroup->load(['accountClass.accountingNature']);
    }

    public function update(AccountingGroup $accountingGroup, UpdateAccountingGroupDTO $dto): AccountingGroup
    {
        $accountingGroup->update([
            'code' => $dto->code,
            'account_class_id' => $dto->accountClassId,
            'name' => $dto->name,
            'description' => $dto->description,
            'account_from' => $dto->accountFrom,
            'account_to' => $dto->accountTo,
            'affects_closing' => $dto->affectsClosing,
            'affects_financial_statements' => $dto->affectsFinancialStatements,
        ]);

        return $accountingGroup->load(['accountClass.accountingNature']);
    }

    public function delete(AccountingGroup $accountingGroup): void
    {
        $accountingGroup->delete();
    }

    public function restore(AccountingGroup $accountingGroup): AccountingGroup
    {
        $accountingGroup->restore();

        return $accountingGroup->load(['accountClass.accountingNature']);
    }
}
