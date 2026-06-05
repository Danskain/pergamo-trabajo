<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseVariationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_exercise' => $this->start_exercise,
            'end_exercise' => $this->end_exercise,
            'normal_periods' => $this->normal_periods,
            'special_periods' => $this->special_periods,
            'calendar_dependent' => (bool) $this->calendar_dependent,
            'description' => $this->description,
            'start_month' => $this->whenLoaded('startMonth', fn (): array => [
                'id' => $this->startMonth->id,
                'name' => $this->startMonth->name,
            ]),
            'end_month' => $this->whenLoaded('endMonth', fn (): array => [
                'id' => $this->endMonth->id,
                'name' => $this->endMonth->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
