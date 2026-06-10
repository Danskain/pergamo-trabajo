<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartAccountResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'accounting_standard_id' => $this->accounting_standard_id,
            'types_plan_id' => $this->types_plan_id,
            'ceco_permission' => $this->ceco_permission,
            'accounting_standard' => $this->whenLoaded('accountingStandard', function (): array {
                return [
                    'id' => $this->accountingStandard->id,
                    'name' => $this->accountingStandard->name,
                    'code' => $this->accountingStandard->code,
                ];
            }),
            'type_plan' => $this->whenLoaded('typePlan', function (): array {
                return [
                    'id' => $this->typePlan->id,
                    'name' => $this->typePlan->name,
                    'code' => $this->typePlan->code,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
