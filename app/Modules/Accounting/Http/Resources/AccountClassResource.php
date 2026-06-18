<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountClassResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'accounting_nature_id' => $this->accounting_nature_id,
            'description' => $this->description,
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
