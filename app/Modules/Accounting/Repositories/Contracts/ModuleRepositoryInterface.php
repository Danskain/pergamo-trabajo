<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateModuleDTO;
use App\Modules\Accounting\DTOs\UpdateModuleDTO;
use App\Modules\Accounting\Models\Module;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ModuleRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): Module;

    public function findTrashedOrFail(int $id): Module;

    public function create(CreateModuleDTO $dto): Module;

    public function update(Module $module, UpdateModuleDTO $dto): Module;

    public function delete(Module $module): void;

    public function restore(Module $module): Module;
}
