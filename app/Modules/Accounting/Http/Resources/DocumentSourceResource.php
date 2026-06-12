<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentSourceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_structure_id' => $this->business_structure_id,
            'modules_id' => $this->modules_id,
            'document_source_type_id' => $this->document_source_type_id,
            'number_document_source' => $this->number_document_source,
            'document_date' => $this->document_date?->toISOString(),
            'accounting_date' => $this->accounting_date?->toISOString(),
            'reference_id' => $this->reference_id,
            'total_value' => $this->total_value,
            'coin_id' => $this->coin_id,
            'financial_statement_id' => $this->financial_statement_id,
            'accounting_document_id' => $this->accounting_document_id,
            'exercise' => $this->exercise,
            'description' => $this->description,
            'business_structure' => $this->whenLoaded('businessStructure', function (): array {
                return [
                    'id' => $this->businessStructure->id,
                    'country_id' => $this->businessStructure->country_id,
                    'coin_id' => $this->businessStructure->coin_id,
                    'enterprise_id' => $this->businessStructure->enterprise_id,
                ];
            }),
            'module' => $this->whenLoaded('module', function (): array {
                return [
                    'id' => $this->module->id,
                    'name' => $this->module->name,
                    'code' => $this->module->code,
                ];
            }),
            'document_source_type' => $this->whenLoaded('documentSourceType', function (): array {
                return [
                    'id' => $this->documentSourceType->id,
                    'name' => $this->documentSourceType->name,
                    'code' => $this->documentSourceType->code,
                ];
            }),
            'reference' => $this->whenLoaded('reference', function (): array {
                return [
                    'id' => $this->reference->id,
                    'name' => $this->reference->name,
                    'code' => $this->reference->code,
                ];
            }),
            'financial_statement' => $this->whenLoaded('financialStatement', function (): array {
                return [
                    'id' => $this->financialStatement->id,
                    'name' => $this->financialStatement->name,
                    'code' => $this->financialStatement->code,
                ];
            }),
            'accounting_document' => $this->whenLoaded('accountingDocument', function (): array {
                return [
                    'id' => $this->accountingDocument->id,
                    'name' => $this->accountingDocument->name,
                    'code' => $this->accountingDocument->code,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
