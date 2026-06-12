<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountingEntryHeaderDTO
{
    public function __construct(
        public readonly int $businessStructureId,
        public readonly int $accountingDocumentId,
        public readonly int $accountingPeriod,
        public readonly int $coinId,
        public readonly string $description,
        public readonly string $totalDebits,
        public readonly string $totalCredits,
        public readonly string $referenceDocument,
        public readonly int $documentsSourceId,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            businessStructureId: $request->integer('business_structure_id'),
            accountingDocumentId: $request->integer('accounting_document_id'),
            accountingPeriod: $request->integer('accounting_period'),
            coinId: $request->integer('coin_id'),
            description: $request->string('description')->toString(),
            totalDebits: $request->string('total_debits')->toString(),
            totalCredits: $request->string('total_credits')->toString(),
            referenceDocument: $request->string('reference_document')->toString(),
            documentsSourceId: $request->integer('documents_source_id'),
        );
    }
}
