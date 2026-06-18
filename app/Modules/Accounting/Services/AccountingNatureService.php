<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingNatureDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingNatureDTO;
use App\Modules\Accounting\Models\AccountingNature;
use App\Modules\Accounting\Repositories\Contracts\AccountingNatureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingNatureService
{
    public function __construct(
        protected AccountingNatureRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingNature
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingNature
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingNatureDTO $dto): AccountingNature
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingNature $accountingNature, UpdateAccountingNatureDTO $dto): AccountingNature
    {
        return $this->repository->update($accountingNature, $dto);
    }

    public function delete(AccountingNature $accountingNature): void
    {
        $this->repository->delete($accountingNature);
    }

    public function restore(AccountingNature $accountingNature): AccountingNature
    {
        return $this->repository->restore($accountingNature);
    }
}
