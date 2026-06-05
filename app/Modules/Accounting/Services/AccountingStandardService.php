<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingStandardDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingStandardDTO;
use App\Modules\Accounting\Models\AccountingStandard;
use App\Modules\Accounting\Repositories\Contracts\AccountingStandardRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingStandardService
{
    public function __construct(
        protected AccountingStandardRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingStandard
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingStandard
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingStandardDTO $dto): AccountingStandard
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingStandard $accountingStandard, UpdateAccountingStandardDTO $dto): AccountingStandard
    {
        return $this->repository->update($accountingStandard, $dto);
    }

    public function delete(AccountingStandard $accountingStandard): void
    {
        $this->repository->delete($accountingStandard);
    }

    public function restore(AccountingStandard $accountingStandard): AccountingStandard
    {
        return $this->repository->restore($accountingStandard);
    }
}
