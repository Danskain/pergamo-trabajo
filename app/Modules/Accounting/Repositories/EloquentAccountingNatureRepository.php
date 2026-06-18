<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingNatureDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingNatureDTO;
use App\Modules\Accounting\Models\AccountingNature;
use App\Modules\Accounting\Repositories\Contracts\AccountingNatureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingNatureRepository implements AccountingNatureRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingNature::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingNature
    {
        return AccountingNature::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingNature
    {
        return AccountingNature::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateAccountingNatureDTO $dto): AccountingNature
    {
        return AccountingNature::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(AccountingNature $accountingNature, UpdateAccountingNatureDTO $dto): AccountingNature
    {
        $accountingNature->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $accountingNature->refresh();
    }

    public function delete(AccountingNature $accountingNature): void
    {
        $accountingNature->delete();
    }

    public function restore(AccountingNature $accountingNature): AccountingNature
    {
        $accountingNature->restore();

        return $accountingNature->refresh();
    }
}
