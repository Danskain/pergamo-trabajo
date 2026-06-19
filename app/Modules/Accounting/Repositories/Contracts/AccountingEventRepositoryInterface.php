<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingEventDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEventDTO;
use App\Modules\Accounting\Models\AccountingEvent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingEventRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingEvent;

    public function findTrashedOrFail(int $id): AccountingEvent;

    public function create(CreateAccountingEventDTO $dto): AccountingEvent;

    public function update(AccountingEvent $accountingEvent, UpdateAccountingEventDTO $dto): AccountingEvent;

    public function delete(AccountingEvent $accountingEvent): void;

    public function restore(AccountingEvent $accountingEvent): AccountingEvent;
}
