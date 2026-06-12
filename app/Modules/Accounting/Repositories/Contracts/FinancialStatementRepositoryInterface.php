<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateFinancialStatementDTO;
use App\Modules\Accounting\DTOs\UpdateFinancialStatementDTO;
use App\Modules\Accounting\Models\FinancialStatement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FinancialStatementRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): FinancialStatement;

    public function findTrashedOrFail(int $id): FinancialStatement;

    public function create(CreateFinancialStatementDTO $dto): FinancialStatement;

    public function update(FinancialStatement $financialStatement, UpdateFinancialStatementDTO $dto): FinancialStatement;

    public function delete(FinancialStatement $financialStatement): void;

    public function restore(FinancialStatement $financialStatement): FinancialStatement;
}
