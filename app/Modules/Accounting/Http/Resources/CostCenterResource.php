<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostCenterResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_structure_id' => $this->business_structure_id,
            'campus_id' => $this->campus_id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'cost_center_type_id' => $this->cost_center_type_id,
            'cost_center_class_id' => $this->cost_center_class_id,
            'cost_center_nature_id' => $this->cost_center_nature_id,
            'allows_allocation' => (bool) $this->allows_allocation,
            'distributes_costs' => (bool) $this->distributes_costs,
            'functional_unit' => (bool) $this->functional_unit,
            'profit_center' => (bool) $this->profit_center,
            'business_structure' => $this->whenLoaded('businessStructure', function (): array {
                return [
                    'id' => $this->businessStructure->id,
                    'country_id' => $this->businessStructure->country_id,
                    'coin_id' => $this->businessStructure->coin_id,
                    'enterprise_id' => $this->businessStructure->enterprise_id,
                ];
            }),
            'campus' => $this->whenLoaded('campus', function (): array {
                return [
                    'id' => $this->campus->id,
                    'name' => $this->campus->name,
                    'address' => $this->campus->address,
                    'enable_code' => $this->campus->enable_code,
                ];
            }),
            'cost_center_type' => $this->whenLoaded('costCenterType', function (): array {
                return [
                    'id' => $this->costCenterType->id,
                    'name' => $this->costCenterType->name,
                    'code' => $this->costCenterType->code,
                ];
            }),
            'cost_center_class' => $this->whenLoaded('costCenterClass', function (): array {
                return [
                    'id' => $this->costCenterClass->id,
                    'name' => $this->costCenterClass->name,
                    'code' => $this->costCenterClass->code,
                ];
            }),
            'cost_center_nature' => $this->whenLoaded('costCenterNature', function (): array {
                return [
                    'id' => $this->costCenterNature->id,
                    'name' => $this->costCenterNature->name,
                    'code' => $this->costCenterNature->code,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
