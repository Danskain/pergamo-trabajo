<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentSourceDTO
{
    public function __construct(
        public readonly int $businessStructureId,
        public readonly int $modulesId,
        public readonly int $documentSourceTypeId,
        public readonly string $numberDocumentSource,
        public readonly string $documentDate,
        public readonly string $accountingDate,
        public readonly int $referenceId,
        public readonly string $totalValue,
        public readonly int $coinId,
        public readonly int $financialStatementId,
        public readonly int $accountingDocumentId,
        public readonly int $exercise,
        public readonly string $description,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            businessStructureId: $request->integer('business_structure_id'),
            modulesId: $request->integer('modules_id'),
            documentSourceTypeId: $request->integer('document_source_type_id'),
            numberDocumentSource: $request->string('number_document_source')->toString(),
            documentDate: $request->string('document_date')->toString(),
            accountingDate: $request->string('accounting_date')->toString(),
            referenceId: $request->integer('reference_id'),
            totalValue: $request->string('total_value')->toString(),
            coinId: $request->integer('coin_id'),
            financialStatementId: $request->integer('financial_statement_id'),
            accountingDocumentId: $request->integer('accounting_document_id'),
            exercise: $request->integer('exercise'),
            description: $request->string('description')->toString(),
        );
    }
}
