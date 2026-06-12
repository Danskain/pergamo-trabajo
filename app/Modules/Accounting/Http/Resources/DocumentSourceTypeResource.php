<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentSourceTypeResource extends JsonResource
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
            'generates_accounting' => (bool) $this->generates_accounting,
            'manual_entry' => (bool) $this->manual_entry,
            'requires_approval' => (bool) $this->requires_approval,
            'requires_third' => (bool) $this->requires_third,
            'requires_ceco' => (bool) $this->requires_ceco,
            'affects_inventory' => (bool) $this->affects_inventory,
            'affects_cartera' => (bool) $this->affects_cartera,
            'affects_cxp' => (bool) $this->affects_cxp,
            'affects_treasury' => (bool) $this->affects_treasury,
            'allows_reversal' => (bool) $this->allows_reversal,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
