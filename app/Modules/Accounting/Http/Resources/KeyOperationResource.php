<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyOperationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'module_id' => $this->module_id,
            'accounting_nature_id' => $this->accounting_nature_id,
            'affects_taxes' => $this->affects_taxes,
            'module' => $this->whenLoaded('module', function (): array {
                return [
                    'id' => $this->module->id,
                    'name' => $this->module->name,
                    'code' => $this->module->code,
                    'description' => $this->module->description,
                ];
            }),
            'accounting_nature' => $this->whenLoaded('accountingNature', function (): array {
                return [
                    'id' => $this->accountingNature->id,
                    'name' => $this->accountingNature->name,
                    'code' => $this->accountingNature->code,
                    'description' => $this->accountingNature->description,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
