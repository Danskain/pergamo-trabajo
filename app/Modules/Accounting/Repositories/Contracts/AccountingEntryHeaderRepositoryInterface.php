<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingEntryHeaderDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryHeaderDTO;
use App\Modules\Accounting\Models\AccountingEntryHeader;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingEntryHeaderRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingEntryHeader;

    public function findTrashedOrFail(int $id): AccountingEntryHeader;

    public function create(CreateAccountingEntryHeaderDTO $dto): AccountingEntryHeader;

    public function update(AccountingEntryHeader $accountingEntryHeader, UpdateAccountingEntryHeaderDTO $dto): AccountingEntryHeader;

    public function delete(AccountingEntryHeader $accountingEntryHeader): void;

    public function restore(AccountingEntryHeader $accountingEntryHeader): AccountingEntryHeader;
}
