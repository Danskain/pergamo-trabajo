<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingEntryPositionDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryPositionDTO;
use App\Modules\Accounting\Models\AccountingEntryPosition;
use App\Modules\Accounting\Repositories\Contracts\AccountingEntryPositionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingEntryPositionService
{
    public function __construct(
        protected AccountingEntryPositionRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingEntryPosition
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingEntryPosition
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingEntryPositionDTO $dto): AccountingEntryPosition
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingEntryPosition $accountingEntryPosition, UpdateAccountingEntryPositionDTO $dto): AccountingEntryPosition
    {
        return $this->repository->update($accountingEntryPosition, $dto);
    }

    public function delete(AccountingEntryPosition $accountingEntryPosition): void
    {
        $this->repository->delete($accountingEntryPosition);
    }

    public function restore(AccountingEntryPosition $accountingEntryPosition): AccountingEntryPosition
    {
        return $this->repository->restore($accountingEntryPosition);
    }
}
