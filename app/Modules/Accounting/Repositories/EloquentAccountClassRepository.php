<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountClassDTO;
use App\Modules\Accounting\DTOs\UpdateAccountClassDTO;
use App\Modules\Accounting\Models\AccountClass;
use App\Modules\Accounting\Repositories\Contracts\AccountClassRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountClassRepository implements AccountClassRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'accountingNature',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountClass::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountClass
    {
        return AccountClass::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountClass
    {
        return AccountClass::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateAccountClassDTO $dto): AccountClass
    {
        $accountClass = AccountClass::query()->create([
            'name' => $dto->name,
            'accounting_nature_id' => $dto->accountingNatureId,
            'description' => $dto->description,
        ]);

        return $accountClass->load($this->with);
    }

    public function update(AccountClass $accountClass, UpdateAccountClassDTO $dto): AccountClass
    {
        $accountClass->update([
            'name' => $dto->name,
            'accounting_nature_id' => $dto->accountingNatureId,
            'description' => $dto->description,
        ]);

        return $accountClass->load($this->with);
    }

    public function delete(AccountClass $accountClass): void
    {
        $accountClass->delete();
    }

    public function restore(AccountClass $accountClass): AccountClass
    {
        $accountClass->restore();

        return $accountClass->load($this->with);
    }
}
