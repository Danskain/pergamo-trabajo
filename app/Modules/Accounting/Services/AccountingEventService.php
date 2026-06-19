<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingEventDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEventDTO;
use App\Modules\Accounting\Models\AccountingEvent;
use App\Modules\Accounting\Repositories\Contracts\AccountingEventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingEventService
{
    public function __construct(
        protected AccountingEventRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingEvent
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingEvent
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingEventDTO $dto): AccountingEvent
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingEvent $accountingEvent, UpdateAccountingEventDTO $dto): AccountingEvent
    {
        return $this->repository->update($accountingEvent, $dto);
    }

    public function delete(AccountingEvent $accountingEvent): void
    {
        $this->repository->delete($accountingEvent);
    }

    public function restore(AccountingEvent $accountingEvent): AccountingEvent
    {
        return $this->repository->restore($accountingEvent);
    }
}
