<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateKeyOperationDTO;
use App\Modules\Accounting\DTOs\UpdateKeyOperationDTO;
use App\Modules\Accounting\Models\KeyOperation;
use App\Modules\Accounting\Repositories\Contracts\KeyOperationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class KeyOperationService
{
    public function __construct(
        protected KeyOperationRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): KeyOperation
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): KeyOperation
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateKeyOperationDTO $dto): KeyOperation
    {
        return $this->repository->create($dto);
    }

    public function update(KeyOperation $keyOperation, UpdateKeyOperationDTO $dto): KeyOperation
    {
        return $this->repository->update($keyOperation, $dto);
    }

    public function delete(KeyOperation $keyOperation): void
    {
        $this->repository->delete($keyOperation);
    }

    public function restore(KeyOperation $keyOperation): KeyOperation
    {
        return $this->repository->restore($keyOperation);
    }
}
