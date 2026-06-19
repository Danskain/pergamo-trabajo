<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateDocumentSourceDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceDTO;
use App\Modules\Accounting\Models\DocumentSource;
use App\Modules\Accounting\Repositories\Contracts\DocumentSourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentDocumentSourceRepository implements DocumentSourceRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'businessStructure',
        'businessStructure.enterprise',
        'module',
        'documentSourceType',
        'reference',
        'financialStatement',
        'accountingDocument',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return DocumentSource::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): DocumentSource
    {
        return DocumentSource::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): DocumentSource
    {
        return DocumentSource::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateDocumentSourceDTO $dto): DocumentSource
    {
        $documentSource = DocumentSource::query()->create([
            'business_structure_id' => $dto->businessStructureId,
            'modules_id' => $dto->modulesId,
            'document_source_type_id' => $dto->documentSourceTypeId,
            'number_document_source' => $dto->numberDocumentSource,
            'document_date' => $dto->documentDate,
            'accounting_date' => $dto->accountingDate,
            'reference_id' => $dto->referenceId,
            'total_value' => $dto->totalValue,
            'coin_id' => $dto->coinId,
            'financial_statement_id' => $dto->financialStatementId,
            'accounting_document_id' => $dto->accountingDocumentId,
            'exercise' => $dto->exercise,
            'description' => $dto->description,
        ]);

        return $documentSource->load($this->with);
    }

    public function update(DocumentSource $documentSource, UpdateDocumentSourceDTO $dto): DocumentSource
    {
        $documentSource->update([
            'business_structure_id' => $dto->businessStructureId,
            'modules_id' => $dto->modulesId,
            'document_source_type_id' => $dto->documentSourceTypeId,
            'number_document_source' => $dto->numberDocumentSource,
            'document_date' => $dto->documentDate,
            'accounting_date' => $dto->accountingDate,
            'reference_id' => $dto->referenceId,
            'total_value' => $dto->totalValue,
            'coin_id' => $dto->coinId,
            'financial_statement_id' => $dto->financialStatementId,
            'accounting_document_id' => $dto->accountingDocumentId,
            'exercise' => $dto->exercise,
            'description' => $dto->description,
        ]);

        return $documentSource->load($this->with);
    }

    public function delete(DocumentSource $documentSource): void
    {
        $documentSource->delete();
    }

    public function restore(DocumentSource $documentSource): DocumentSource
    {
        $documentSource->restore();

        return $documentSource->load($this->with);
    }
}
