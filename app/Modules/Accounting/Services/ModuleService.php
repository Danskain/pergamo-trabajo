<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateModuleDTO;
use App\Modules\Accounting\DTOs\UpdateModuleDTO;
use App\Modules\Accounting\Models\Module;
use App\Modules\Accounting\Repositories\Contracts\ModuleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ModuleService
{
    public function __construct(
        protected ModuleRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): Module
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): Module
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateModuleDTO $dto): Module
    {
        return $this->repository->create($dto);
    }

    public function update(Module $module, UpdateModuleDTO $dto): Module
    {
        return $this->repository->update($module, $dto);
    }

    public function delete(Module $module): void
    {
        $this->repository->delete($module);
    }

    public function restore(Module $module): Module
    {
        return $this->repository->restore($module);
    }
}
