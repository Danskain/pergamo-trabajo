<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingEntryHeaderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_structure_id' => $this->business_structure_id,
            'accounting_document_id' => $this->accounting_document_id,
            'accounting_period' => $this->accounting_period,
            'coin_id' => $this->coin_id,
            'description' => $this->description,
            'total_debits' => $this->total_debits,
            'total_credits' => $this->total_credits,
            'reference_document' => $this->reference_document,
            'documents_source_id' => $this->documents_source_id,
            'business_structure' => $this->whenLoaded('businessStructure', function (): array {
                return [
                    'id' => $this->businessStructure->id,
                    'country_id' => $this->businessStructure->country_id,
                    'coin_id' => $this->businessStructure->coin_id,
                    'enterprise_id' => $this->businessStructure->enterprise_id,
                    'enterprise' => $this->businessStructure->relationLoaded('enterprise') && $this->businessStructure->enterprise !== null
                        ? [
                            'id' => $this->businessStructure->enterprise->id,
                            'name' => $this->businessStructure->enterprise->name,
                        ]
                        : null,
                ];
            }),
            'coin' => $this->whenLoaded('coin', function (): array {
                return [
                    'id' => $this->coin->id,
                    'name' => $this->coin->name,
                    'alphabetic_code' => $this->coin->alphabetic_code,
                    'numeric_code' => $this->coin->numeric_code,
                ];
            }),
            'accounting_document' => $this->whenLoaded('accountingDocument', function (): array {
                return [
                    'id' => $this->accountingDocument->id,
                    'name' => $this->accountingDocument->name,
                    'code' => $this->accountingDocument->code,
                ];
            }),
            'document_source' => $this->whenLoaded('documentSource', function (): array {
                return [
                    'id' => $this->documentSource->id,
                    'number_document_source' => $this->documentSource->number_document_source,
                    'exercise' => $this->documentSource->exercise,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
