<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountingDocumentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingDocumentDTO;
use App\Modules\Accounting\Models\AccountingDocument;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountingDocumentRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountingDocument;

    public function findTrashedOrFail(int $id): AccountingDocument;

    public function create(CreateAccountingDocumentDTO $dto): AccountingDocument;

    public function update(AccountingDocument $accountingDocument, UpdateAccountingDocumentDTO $dto): AccountingDocument;

    public function delete(AccountingDocument $accountingDocument): void;

    public function restore(AccountingDocument $accountingDocument): AccountingDocument;
}
