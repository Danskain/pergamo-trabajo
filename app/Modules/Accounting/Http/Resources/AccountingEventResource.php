<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingEventResource extends JsonResource
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
            'accounting_moment_id' => $this->accounting_moment_id,
            'origin' => $this->origin,
            'accounting_moment' => $this->whenLoaded('accountingMoment', function (): array {
                return [
                    'id' => $this->accountingMoment->id,
                    'name' => $this->accountingMoment->name,
                    'code' => $this->accountingMoment->code,
                    'description' => $this->accountingMoment->description,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
