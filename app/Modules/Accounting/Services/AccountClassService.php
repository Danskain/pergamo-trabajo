<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountClassDTO;
use App\Modules\Accounting\DTOs\UpdateAccountClassDTO;
use App\Modules\Accounting\Models\AccountClass;
use App\Modules\Accounting\Repositories\Contracts\AccountClassRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountClassService
{
    public function __construct(
        protected AccountClassRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountClass
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountClass
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountClassDTO $dto): AccountClass
    {
        return $this->repository->create($dto);
    }

    public function update(AccountClass $accountClass, UpdateAccountClassDTO $dto): AccountClass
    {
        return $this->repository->update($accountClass, $dto);
    }

    public function delete(AccountClass $accountClass): void
    {
        $this->repository->delete($accountClass);
    }

    public function restore(AccountClass $accountClass): AccountClass
    {
        return $this->repository->restore($accountClass);
    }
}
