<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateBusinessStructureDTO;
use App\Modules\Accounting\DTOs\UpdateBusinessStructureDTO;
use App\Modules\Accounting\Models\BusinessStructure;
use App\Modules\Accounting\Repositories\Contracts\BusinessStructureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BusinessStructureService
{
    public function __construct(
        protected BusinessStructureRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): BusinessStructure
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): BusinessStructure
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateBusinessStructureDTO $dto): BusinessStructure
    {
        return $this->repository->create($dto);
    }

    public function update(BusinessStructure $businessStructure, UpdateBusinessStructureDTO $dto): BusinessStructure
    {
        return $this->repository->update($businessStructure, $dto);
    }

    public function delete(BusinessStructure $businessStructure): void
    {
        $this->repository->delete($businessStructure);
    }

    public function restore(BusinessStructure $businessStructure): BusinessStructure
    {
        return $this->repository->restore($businessStructure);
    }
}
