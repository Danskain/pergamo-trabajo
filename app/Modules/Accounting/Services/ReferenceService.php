<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateReferenceDTO;
use App\Modules\Accounting\DTOs\UpdateReferenceDTO;
use App\Modules\Accounting\Models\Reference;
use App\Modules\Accounting\Repositories\Contracts\ReferenceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReferenceService
{
    public function __construct(
        protected ReferenceRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): Reference
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): Reference
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateReferenceDTO $dto): Reference
    {
        return $this->repository->create($dto);
    }

    public function update(Reference $reference, UpdateReferenceDTO $dto): Reference
    {
        return $this->repository->update($reference, $dto);
    }

    public function delete(Reference $reference): void
    {
        $this->repository->delete($reference);
    }

    public function restore(Reference $reference): Reference
    {
        return $this->repository->restore($reference);
    }
}
