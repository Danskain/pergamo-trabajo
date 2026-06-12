<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateModuleDTO;
use App\Modules\Accounting\DTOs\UpdateModuleDTO;
use App\Modules\Accounting\Models\Module;
use App\Modules\Accounting\Repositories\Contracts\ModuleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentModuleRepository implements ModuleRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return Module::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Module
    {
        return Module::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): Module
    {
        return Module::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateModuleDTO $dto): Module
    {
        return Module::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(Module $module, UpdateModuleDTO $dto): Module
    {
        $module->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $module->refresh();
    }

    public function delete(Module $module): void
    {
        $module->delete();
    }

    public function restore(Module $module): Module
    {
        $module->restore();

        return $module->refresh();
    }
}
