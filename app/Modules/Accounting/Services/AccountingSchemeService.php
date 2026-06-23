<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingSchemeDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingSchemeDTO;
use App\Modules\Accounting\Models\AccountingScheme;
use App\Modules\Accounting\Repositories\Contracts\AccountingSchemeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingSchemeService
{
    public function __construct(
        protected AccountingSchemeRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingScheme
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingScheme
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingSchemeDTO $dto): AccountingScheme
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingScheme $accountingScheme, UpdateAccountingSchemeDTO $dto): AccountingScheme
    {
        return $this->repository->update($accountingScheme, $dto);
    }

    public function delete(AccountingScheme $accountingScheme): void
    {
        $this->repository->delete($accountingScheme);
    }

    public function restore(AccountingScheme $accountingScheme): AccountingScheme
    {
        return $this->repository->restore($accountingScheme);
    }
}
