<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateFinancialStatementDTO;
use App\Modules\Accounting\DTOs\UpdateFinancialStatementDTO;
use App\Modules\Accounting\Models\FinancialStatement;
use App\Modules\Accounting\Repositories\Contracts\FinancialStatementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentFinancialStatementRepository implements FinancialStatementRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return FinancialStatement::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): FinancialStatement
    {
        return FinancialStatement::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): FinancialStatement
    {
        return FinancialStatement::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateFinancialStatementDTO $dto): FinancialStatement
    {
        return FinancialStatement::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(FinancialStatement $financialStatement, UpdateFinancialStatementDTO $dto): FinancialStatement
    {
        $financialStatement->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $financialStatement->refresh();
    }

    public function delete(FinancialStatement $financialStatement): void
    {
        $financialStatement->delete();
    }

    public function restore(FinancialStatement $financialStatement): FinancialStatement
    {
        $financialStatement->restore();

        return $financialStatement->refresh();
    }
}
