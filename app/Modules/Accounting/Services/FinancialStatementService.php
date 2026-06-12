<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateFinancialStatementDTO;
use App\Modules\Accounting\DTOs\UpdateFinancialStatementDTO;
use App\Modules\Accounting\Models\FinancialStatement;
use App\Modules\Accounting\Repositories\Contracts\FinancialStatementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FinancialStatementService
{
    public function __construct(
        protected FinancialStatementRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): FinancialStatement
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): FinancialStatement
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateFinancialStatementDTO $dto): FinancialStatement
    {
        return $this->repository->create($dto);
    }

    public function update(FinancialStatement $financialStatement, UpdateFinancialStatementDTO $dto): FinancialStatement
    {
        return $this->repository->update($financialStatement, $dto);
    }

    public function delete(FinancialStatement $financialStatement): void
    {
        $this->repository->delete($financialStatement);
    }

    public function restore(FinancialStatement $financialStatement): FinancialStatement
    {
        return $this->repository->restore($financialStatement);
    }
}
