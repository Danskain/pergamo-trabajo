<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingEntryPositionDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryPositionDTO;
use App\Modules\Accounting\Models\AccountingEntryPosition;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingEntryPositionRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingEntryPosition;

    public function findTrashedOrFail(int $id): AccountingEntryPosition;

    public function create(CreateAccountingEntryPositionDTO $dto): AccountingEntryPosition;

    public function update(AccountingEntryPosition $accountingEntryPosition, UpdateAccountingEntryPositionDTO $dto): AccountingEntryPosition;

    public function delete(AccountingEntryPosition $accountingEntryPosition): void;

    public function restore(AccountingEntryPosition $accountingEntryPosition): AccountingEntryPosition;
}
