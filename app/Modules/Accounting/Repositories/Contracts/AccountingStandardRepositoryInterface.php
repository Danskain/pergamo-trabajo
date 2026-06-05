<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingStandardDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingStandardDTO;
use App\Modules\Accounting\Models\AccountingStandard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingStandardRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingStandard;

    public function findTrashedOrFail(int $id): AccountingStandard;

    public function create(CreateAccountingStandardDTO $dto): AccountingStandard;

    public function update(AccountingStandard $accountingStandard, UpdateAccountingStandardDTO $dto): AccountingStandard;

    public function delete(AccountingStandard $accountingStandard): void;

    public function restore(AccountingStandard $accountingStandard): AccountingStandard;
}
