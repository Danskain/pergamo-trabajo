<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingGroupDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingGroupDTO;
use App\Modules\Accounting\Models\AccountingGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingGroupRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingGroup;

    public function findTrashedOrFail(int $id): AccountingGroup;

    public function create(CreateAccountingGroupDTO $dto): AccountingGroup;

    public function update(AccountingGroup $accountingGroup, UpdateAccountingGroupDTO $dto): AccountingGroup;

    public function delete(AccountingGroup $accountingGroup): void;

    public function restore(AccountingGroup $accountingGroup): AccountingGroup;
}
