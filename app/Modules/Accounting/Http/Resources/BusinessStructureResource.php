<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessStructureResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'coin_id' => $this->coin_id,
            'enterprise_id' => $this->enterprise_id,
            'exercise_variation_id' => $this->exercise_variation_id,
            'chart_account_id' => $this->chart_account_id,
            'exercise_variation' => $this->whenLoaded('exerciseVariation', function (): array {
                return [
                    'id' => $this->exerciseVariation->id,
                    'name' => $this->exerciseVariation->name,
                    'code' => $this->exerciseVariation->code,
                ];
            }),
            'chart_account' => $this->whenLoaded('chartAccount', function (): array {
                return [
                    'id' => $this->chartAccount->id,
                    'name' => $this->chartAccount->name,
                    'code' => $this->chartAccount->code,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
