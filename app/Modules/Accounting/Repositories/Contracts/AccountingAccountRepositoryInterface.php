<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingAccountDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingAccountDTO;
use App\Modules\Accounting\Models\AccountingAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingAccountRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingAccount;

    public function findTrashedOrFail(int $id): AccountingAccount;

    public function create(CreateAccountingAccountDTO $dto): AccountingAccount;

    public function update(AccountingAccount $accountingAccount, UpdateAccountingAccountDTO $dto): AccountingAccount;

    public function delete(AccountingAccount $accountingAccount): void;

    public function restore(AccountingAccount $accountingAccount): AccountingAccount;
}
