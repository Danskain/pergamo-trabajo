<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingNatureDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingNatureDTO;
use App\Modules\Accounting\Models\AccountingNature;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingNatureRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingNature;

    public function findTrashedOrFail(int $id): AccountingNature;

    public function create(CreateAccountingNatureDTO $dto): AccountingNature;

    public function update(AccountingNature $accountingNature, UpdateAccountingNatureDTO $dto): AccountingNature;

    public function delete(AccountingNature $accountingNature): void;

    public function restore(AccountingNature $accountingNature): AccountingNature;
}
