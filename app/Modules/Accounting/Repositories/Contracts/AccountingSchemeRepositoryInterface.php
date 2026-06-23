<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingSchemeDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingSchemeDTO;
use App\Modules\Accounting\Models\AccountingScheme;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingSchemeRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingScheme;

    public function findTrashedOrFail(int $id): AccountingScheme;

    public function create(CreateAccountingSchemeDTO $dto): AccountingScheme;

    public function update(AccountingScheme $accountingScheme, UpdateAccountingSchemeDTO $dto): AccountingScheme;

    public function delete(AccountingScheme $accountingScheme): void;

    public function restore(AccountingScheme $accountingScheme): AccountingScheme;
}
