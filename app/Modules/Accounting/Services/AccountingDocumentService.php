<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateAccountingDocumentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingDocumentDTO;
use App\Modules\Accounting\Models\AccountingDocument;
use App\Modules\Accounting\Repositories\Contracts\AccountingDocumentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountingDocumentService
{
    public function __construct(
        protected AccountingDocumentRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): AccountingDocument
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingDocument
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateAccountingDocumentDTO $dto): AccountingDocument
    {
        return $this->repository->create($dto);
    }

    public function update(AccountingDocument $accountingDocument, UpdateAccountingDocumentDTO $dto): AccountingDocument
    {
        return $this->repository->update($accountingDocument, $dto);
    }

    public function delete(AccountingDocument $accountingDocument): void
    {
        $this->repository->delete($accountingDocument);
    }

    public function restore(AccountingDocument $accountingDocument): AccountingDocument
    {
        return $this->repository->restore($accountingDocument);
    }
}
