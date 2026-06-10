<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingAccountDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingAccountDTO;
use App\Modules\Accounting\Models\AccountingAccount;
use App\Modules\Accounting\Repositories\Contracts\AccountingAccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingAccountService
{
    public function __construct(
        protected AccountingAccountRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingAccount
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingAccount
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingAccountDTO $dto): AccountingAccount
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingAccount $accountingAccount, UpdateAccountingAccountDTO $dto): AccountingAccount
    {
        return $this->repository->update($accountingAccount, $dto);
    }

    public function delete(AccountingAccount $accountingAccount): void
    {
        $this->repository->delete($accountingAccount);
    }

    public function restore(AccountingAccount $accountingAccount): AccountingAccount
    {
        return $this->repository->restore($accountingAccount);
    }
}
