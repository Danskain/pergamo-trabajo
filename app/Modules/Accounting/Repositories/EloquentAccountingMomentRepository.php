<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingMomentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingMomentDTO;
use App\Modules\Accounting\Models\AccountingMoment;
use App\Modules\Accounting\Repositories\Contracts\AccountingMomentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingMomentRepository implements AccountingMomentRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingMoment::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingMoment
    {
        return AccountingMoment::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingMoment
    {
        return AccountingMoment::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateAccountingMomentDTO $dto): AccountingMoment
    {
        return AccountingMoment::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(AccountingMoment $accountingMoment, UpdateAccountingMomentDTO $dto): AccountingMoment
    {
        $accountingMoment->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $accountingMoment;
    }

    public function delete(AccountingMoment $accountingMoment): void
    {
        $accountingMoment->delete();
    }

    public function restore(AccountingMoment $accountingMoment): AccountingMoment
    {
        $accountingMoment->restore();

        return $accountingMoment;
    }
}
