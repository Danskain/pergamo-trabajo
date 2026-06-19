<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingMomentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingMomentDTO;
use App\Modules\Accounting\Models\AccountingMoment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingMomentRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingMoment;

    public function findTrashedOrFail(int $id): AccountingMoment;

    public function create(CreateAccountingMomentDTO $dto): AccountingMoment;

    public function update(AccountingMoment $accountingMoment, UpdateAccountingMomentDTO $dto): AccountingMoment;

    public function delete(AccountingMoment $accountingMoment): void;

    public function restore(AccountingMoment $accountingMoment): AccountingMoment;
}
