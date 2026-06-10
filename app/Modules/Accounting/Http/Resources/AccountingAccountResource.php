<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingAccountResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account' => $this->account,
            'chart_account_id' => $this->chart_account_id,
            'name' => $this->name,
            'account_class_id' => $this->account_class_id,
            'types_account_id' => $this->types_account_id,
            'accounting_group_id' => $this->accounting_group_id,
            'allows_manual_transactions' => $this->allows_manual_transactions,
            'associated_account' => $this->associated_account,
            'accepts_taxes' => $this->accepts_taxes,
            'foreign_currency' => $this->foreign_currency,
            'chart_account' => $this->whenLoaded('chartAccount', function (): array {
                return [
                    'id' => $this->chartAccount->id,
                    'name' => $this->chartAccount->name,
                    'code' => $this->chartAccount->code,
                ];
            }),
            'account_class' => $this->whenLoaded('accountClass', function (): array {
                return [
                    'id' => $this->accountClass->id,
                    'name' => $this->accountClass->name,
                ];
            }),
            'type_account' => $this->whenLoaded('typeAccount', function (): array {
                return [
                    'id' => $this->typeAccount->id,
                    'name' => $this->typeAccount->name,
                    'code' => $this->typeAccount->code,
                ];
            }),
            'accounting_group' => $this->whenLoaded('accountingGroup', function (): array {
                return [
                    'id' => $this->accountingGroup->id,
                    'name' => $this->accountingGroup->name,
                    'code' => $this->accountingGroup->code,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
