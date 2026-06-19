<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingEntryHeaderDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryHeaderDTO;
use App\Modules\Accounting\Models\AccountingEntryHeader;
use App\Modules\Accounting\Repositories\Contracts\AccountingEntryHeaderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingEntryHeaderRepository implements AccountingEntryHeaderRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'businessStructure',
        'businessStructure.enterprise',
        'accountingDocument',
        'coin',
        'documentSource',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingEntryHeader::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingEntryHeader
    {
        return AccountingEntryHeader::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingEntryHeader
    {
        return AccountingEntryHeader::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateAccountingEntryHeaderDTO $dto): AccountingEntryHeader
    {
        $accountingEntryHeader = AccountingEntryHeader::query()->create([
            'business_structure_id' => $dto->businessStructureId,
            'accounting_document_id' => $dto->accountingDocumentId,
            'accounting_period' => $dto->accountingPeriod,
            'coin_id' => $dto->coinId,
            'description' => $dto->description,
            'total_debits' => $dto->totalDebits,
            'total_credits' => $dto->totalCredits,
            'reference_document' => $dto->referenceDocument,
            'documents_source_id' => $dto->documentsSourceId,
        ]);

        return $accountingEntryHeader->load($this->with);
    }

    public function update(AccountingEntryHeader $accountingEntryHeader, UpdateAccountingEntryHeaderDTO $dto): AccountingEntryHeader
    {
        $accountingEntryHeader->update([
            'business_structure_id' => $dto->businessStructureId,
            'accounting_document_id' => $dto->accountingDocumentId,
            'accounting_period' => $dto->accountingPeriod,
            'coin_id' => $dto->coinId,
            'description' => $dto->description,
            'total_debits' => $dto->totalDebits,
            'total_credits' => $dto->totalCredits,
            'reference_document' => $dto->referenceDocument,
            'documents_source_id' => $dto->documentsSourceId,
        ]);

        return $accountingEntryHeader->load($this->with);
    }

    public function delete(AccountingEntryHeader $accountingEntryHeader): void
    {
        $accountingEntryHeader->delete();
    }

    public function restore(AccountingEntryHeader $accountingEntryHeader): AccountingEntryHeader
    {
        $accountingEntryHeader->restore();

        return $accountingEntryHeader->load($this->with);
    }
}
