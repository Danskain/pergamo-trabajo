<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateTypeAccountDTO;
use App\Modules\Accounting\DTOs\UpdateTypeAccountDTO;
use App\Modules\Accounting\Models\TypeAccount;
use App\Modules\Accounting\Repositories\Contracts\TypeAccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTypeAccountRepository implements TypeAccountRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return TypeAccount::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): TypeAccount
    {
        return TypeAccount::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): TypeAccount
    {
        return TypeAccount::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateTypeAccountDTO $dto): TypeAccount
    {
        return TypeAccount::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(TypeAccount $typeAccount, UpdateTypeAccountDTO $dto): TypeAccount
    {
        $typeAccount->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $typeAccount->refresh();
    }

    public function delete(TypeAccount $typeAccount): void
    {
        $typeAccount->delete();
    }

    public function restore(TypeAccount $typeAccount): TypeAccount
    {
        $typeAccount->restore();

        return $typeAccount->refresh();
    }
}
