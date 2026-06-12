<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingEntryHeaderDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryHeaderDTO;
use App\Modules\Accounting\Models\AccountingEntryHeader;
use App\Modules\Accounting\Repositories\Contracts\AccountingEntryHeaderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingEntryHeaderService
{
    public function __construct(
        protected AccountingEntryHeaderRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingEntryHeader
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingEntryHeader
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingEntryHeaderDTO $dto): AccountingEntryHeader
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingEntryHeader $accountingEntryHeader, UpdateAccountingEntryHeaderDTO $dto): AccountingEntryHeader
    {
        return $this->repository->update($accountingEntryHeader, $dto);
    }

    public function delete(AccountingEntryHeader $accountingEntryHeader): void
    {
        $this->repository->delete($accountingEntryHeader);
    }

    public function restore(AccountingEntryHeader $accountingEntryHeader): AccountingEntryHeader
    {
        return $this->repository->restore($accountingEntryHeader);
    }
}
