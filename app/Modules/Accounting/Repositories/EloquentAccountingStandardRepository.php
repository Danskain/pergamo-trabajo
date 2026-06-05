<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingStandardDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingStandardDTO;
use App\Modules\Accounting\Models\AccountingStandard;
use App\Modules\Accounting\Repositories\Contracts\AccountingStandardRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingStandardRepository implements AccountingStandardRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingStandard::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingStandard
    {
        return AccountingStandard::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingStandard
    {
        return AccountingStandard::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateAccountingStandardDTO $dto): AccountingStandard
    {
        return AccountingStandard::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(AccountingStandard $accountingStandard, UpdateAccountingStandardDTO $dto): AccountingStandard
    {
        $accountingStandard->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $accountingStandard->refresh();
    }

    public function delete(AccountingStandard $accountingStandard): void
    {
        $accountingStandard->delete();
    }

    public function restore(AccountingStandard $accountingStandard): AccountingStandard
    {
        $accountingStandard->restore();

        return $accountingStandard->refresh();
    }
}
