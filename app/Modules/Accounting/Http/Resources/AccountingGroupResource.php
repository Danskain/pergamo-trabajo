<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingGroupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'account_class_id' => $this->account_class_id,
            'name' => $this->name,
            'description' => $this->description,
            'account_from' => $this->account_from,
            'account_to' => $this->account_to,
            'affects_closing' => $this->affects_closing,
            'affects_financial_statements' => $this->affects_financial_statements,
            'account_class' => $this->whenLoaded('accountClass', function (): array {
                return [
                    'id' => $this->accountClass->id,
                    'name' => $this->accountClass->name,
                    'accounting_nature_id' => $this->accountClass->accounting_nature_id,
                    'accounting_nature' => $this->accountClass->relationLoaded('accountingNature')
                        ? [
                            'id' => $this->accountClass->accountingNature->id,
                            'name' => $this->accountClass->accountingNature->name,
                            'code' => $this->accountClass->accountingNature->code,
                        ]
                        : null,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
