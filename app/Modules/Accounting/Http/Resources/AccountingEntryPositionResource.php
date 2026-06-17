<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingEntryPositionResource extends JsonResource
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
            'accounting_entry_header_id' => $this->accounting_entry_header_id,
            'accounting_accounts_id' => $this->accounting_accounts_id,
            'id_tercero' => $this->id_tercero,
            'indicator_dc' => $this->indicator_dc,
            'amount' => $this->amount,
            'coin_id' => $this->coin_id,
            'cost_center_id' => $this->cost_center_id,
            'position_text' => $this->position_text,
            'business_structure' => $this->whenLoaded('businessStructure', function (): array {
                return [
                    'id' => $this->businessStructure->id,
                    'country_id' => $this->businessStructure->country_id,
                    'coin_id' => $this->businessStructure->coin_id,
                    'enterprise_id' => $this->businessStructure->enterprise_id,
                ];
            }),
            'accounting_document' => $this->whenLoaded('accountingDocument', function (): array {
                return [
                    'id' => $this->accountingDocument->id,
                    'name' => $this->accountingDocument->name,
                    'code' => $this->accountingDocument->code,
                ];
            }),
            'accounting_entry_header' => $this->whenLoaded('accountingEntryHeader', function (): array {
                return [
                    'id' => $this->accountingEntryHeader->id,
                    'accounting_period' => $this->accountingEntryHeader->accounting_period,
                    'reference_document' => $this->accountingEntryHeader->reference_document,
                ];
            }),
            'accounting_account' => $this->whenLoaded('accountingAccount', function (): array {
                return [
                    'id' => $this->accountingAccount->id,
                    'account' => $this->accountingAccount->account,
                    'name' => $this->accountingAccount->name,
                ];
            }),
            'cost_center' => $this->whenLoaded('costCenter', function (): ?array {
                if ($this->costCenter === null) {
                    return null;
                }

                return [
                    'id' => $this->costCenter->id,
                    'code' => $this->costCenter->code,
                    'name' => $this->costCenter->name,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
