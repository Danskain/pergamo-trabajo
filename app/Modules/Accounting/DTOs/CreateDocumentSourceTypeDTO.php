<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentSourceTypeDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly string $description,
        public readonly bool $generatesAccounting,
        public readonly bool $manualEntry,
        public readonly bool $requiresApproval,
        public readonly bool $requiresThird,
        public readonly bool $requiresCeco,
        public readonly bool $affectsInventory,
        public readonly bool $affectsCartera,
        public readonly bool $affectsCxp,
        public readonly bool $affectsTreasury,
        public readonly bool $allowsReversal,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            code: $request->string('code')->toString(),
            description: $request->string('description')->toString(),
            generatesAccounting: $request->boolean('generates_accounting'),
            manualEntry: $request->boolean('manual_entry'),
            requiresApproval: $request->boolean('requires_approval'),
            requiresThird: $request->boolean('requires_third'),
            requiresCeco: $request->boolean('requires_ceco'),
            affectsInventory: $request->boolean('affects_inventory'),
            affectsCartera: $request->boolean('affects_cartera'),
            affectsCxp: $request->boolean('affects_cxp'),
            affectsTreasury: $request->boolean('affects_treasury'),
            allowsReversal: $request->boolean('allows_reversal'),
        );
    }
}
