<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingAccountDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingAccountDTO;
use App\Modules\Accounting\Models\AccountingAccount;
use App\Modules\Accounting\Repositories\Contracts\AccountingAccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingAccountRepository implements AccountingAccountRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingAccount::query()
            ->with(['chartAccount', 'accountClass', 'typeAccount', 'accountingGroup'])
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingAccount
    {
        return AccountingAccount::query()
            ->with(['chartAccount', 'accountClass', 'typeAccount', 'accountingGroup'])
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingAccount
    {
        return AccountingAccount::query()
            ->withTrashed()
            ->with(['chartAccount', 'accountClass', 'typeAccount', 'accountingGroup'])
            ->findOrFail($id);
    }

    public function create(CreateAccountingAccountDTO $dto): AccountingAccount
    {
        $accountingAccount = AccountingAccount::query()->create([
            'account' => $dto->account,
            'chart_account_id' => $dto->chartAccountId,
            'name' => $dto->name,
            'account_class_id' => $dto->accountClassId,
            'types_account_id' => $dto->typesAccountId,
            'accounting_group_id' => $dto->accountingGroupId,
            'allows_manual_transactions' => $dto->allowsManualTransactions,
            'associated_account' => $dto->associatedAccount,
            'accepts_taxes' => $dto->acceptsTaxes,
            'foreign_currency' => $dto->foreignCurrency,
        ]);

        return $accountingAccount->load(['chartAccount', 'accountClass', 'typeAccount', 'accountingGroup']);
    }

    public function update(AccountingAccount $accountingAccount, UpdateAccountingAccountDTO $dto): AccountingAccount
    {
        $accountingAccount->update([
            'account' => $dto->account,
            'chart_account_id' => $dto->chartAccountId,
            'name' => $dto->name,
            'account_class_id' => $dto->accountClassId,
            'types_account_id' => $dto->typesAccountId,
            'accounting_group_id' => $dto->accountingGroupId,
            'allows_manual_transactions' => $dto->allowsManualTransactions,
            'associated_account' => $dto->associatedAccount,
            'accepts_taxes' => $dto->acceptsTaxes,
            'foreign_currency' => $dto->foreignCurrency,
        ]);

        return $accountingAccount->load(['chartAccount', 'accountClass', 'typeAccount', 'accountingGroup']);
    }

    public function delete(AccountingAccount $accountingAccount): void
    {
        $accountingAccount->delete();
    }

    public function restore(AccountingAccount $accountingAccount): AccountingAccount
    {
        $accountingAccount->restore();

        return $accountingAccount->load(['chartAccount', 'accountClass', 'typeAccount', 'accountingGroup']);
    }
}
