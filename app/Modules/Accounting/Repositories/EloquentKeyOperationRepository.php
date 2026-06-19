<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateKeyOperationDTO;
use App\Modules\Accounting\DTOs\UpdateKeyOperationDTO;
use App\Modules\Accounting\Models\KeyOperation;
use App\Modules\Accounting\Repositories\Contracts\KeyOperationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentKeyOperationRepository implements KeyOperationRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'module',
        'accountingNature',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return KeyOperation::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): KeyOperation
    {
        return KeyOperation::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): KeyOperation
    {
        return KeyOperation::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateKeyOperationDTO $dto): KeyOperation
    {
        $keyOperation = KeyOperation::query()->create([
            'code' => $dto->code,
            'name' => $dto->name,
            'module_id' => $dto->moduleId,
            'accounting_nature_id' => $dto->accountingNatureId,
            'affects_taxes' => $dto->affectsTaxes,
        ]);

        return $keyOperation->load($this->with);
    }

    public function update(KeyOperation $keyOperation, UpdateKeyOperationDTO $dto): KeyOperation
    {
        $keyOperation->update([
            'code' => $dto->code,
            'name' => $dto->name,
            'module_id' => $dto->moduleId,
            'accounting_nature_id' => $dto->accountingNatureId,
            'affects_taxes' => $dto->affectsTaxes,
        ]);

        return $keyOperation->load($this->with);
    }

    public function delete(KeyOperation $keyOperation): void
    {
        $keyOperation->delete();
    }

    public function restore(KeyOperation $keyOperation): KeyOperation
    {
        $keyOperation->restore();

        return $keyOperation->load($this->with);
    }
}
