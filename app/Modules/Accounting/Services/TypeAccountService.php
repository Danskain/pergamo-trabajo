<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateTypeAccountDTO;
use App\Modules\Accounting\DTOs\UpdateTypeAccountDTO;
use App\Modules\Accounting\Models\TypeAccount;
use App\Modules\Accounting\Repositories\Contracts\TypeAccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TypeAccountService
{
    public function __construct(
        protected TypeAccountRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): TypeAccount
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): TypeAccount
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateTypeAccountDTO $dto): TypeAccount
    {
        return $this->repository->create($dto);
    }

    public function update(TypeAccount $typeAccount, UpdateTypeAccountDTO $dto): TypeAccount
    {
        return $this->repository->update($typeAccount, $dto);
    }

    public function delete(TypeAccount $typeAccount): void
    {
        $this->repository->delete($typeAccount);
    }

    public function restore(TypeAccount $typeAccount): TypeAccount
    {
        return $this->repository->restore($typeAccount);
    }
}
