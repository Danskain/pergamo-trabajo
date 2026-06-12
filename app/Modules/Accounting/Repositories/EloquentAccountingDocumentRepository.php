<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingDocumentDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingDocumentDTO;
use App\Modules\Accounting\Models\AccountingDocument;
use App\Modules\Accounting\Repositories\Contracts\AccountingDocumentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingDocumentRepository implements AccountingDocumentRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingDocument::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingDocument
    {
        return AccountingDocument::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingDocument
    {
        return AccountingDocument::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateAccountingDocumentDTO $dto): AccountingDocument
    {
        return AccountingDocument::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(AccountingDocument $accountingDocument, UpdateAccountingDocumentDTO $dto): AccountingDocument
    {
        $accountingDocument->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $accountingDocument->refresh();
    }

    public function delete(AccountingDocument $accountingDocument): void
    {
        $accountingDocument->delete();
    }

    public function restore(AccountingDocument $accountingDocument): AccountingDocument
    {
        $accountingDocument->restore();

        return $accountingDocument->refresh();
    }
}
