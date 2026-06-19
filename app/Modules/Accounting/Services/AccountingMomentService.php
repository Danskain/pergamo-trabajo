<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingMomentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingMomentDTO;
use App\Modules\Accounting\Models\AccountingMoment;
use App\Modules\Accounting\Repositories\Contracts\AccountingMomentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingMomentService
{
    public function __construct(
        protected AccountingMomentRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingMoment
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingMoment
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingMomentDTO $dto): AccountingMoment
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingMoment $accountingMoment, UpdateAccountingMomentDTO $dto): AccountingMoment
    {
        return $this->repository->update($accountingMoment, $dto);
    }

    public function delete(AccountingMoment $accountingMoment): void
    {
        $this->repository->delete($accountingMoment);
    }

    public function restore(AccountingMoment $accountingMoment): AccountingMoment
    {
        return $this->repository->restore($accountingMoment);
    }
}
