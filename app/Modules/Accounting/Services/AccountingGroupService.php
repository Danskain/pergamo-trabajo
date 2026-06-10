<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingGroupDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingGroupDTO;
use App\Modules\Accounting\Models\AccountingGroup;
use App\Modules\Accounting\Repositories\Contracts\AccountingGroupRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingGroupService
{
    public function __construct(
        protected AccountingGroupRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingGroup
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingGroup
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingGroupDTO $dto): AccountingGroup
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingGroup $accountingGroup, UpdateAccountingGroupDTO $dto): AccountingGroup
    {
        return $this->repository->update($accountingGroup, $dto);
    }

    public function delete(AccountingGroup $accountingGroup): void
    {
        $this->repository->delete($accountingGroup);
    }

    public function restore(AccountingGroup $accountingGroup): AccountingGroup
    {
        return $this->repository->restore($accountingGroup);
    }
}
