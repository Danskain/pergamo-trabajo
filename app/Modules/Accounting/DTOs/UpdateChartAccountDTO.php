<?php

namespace App\Modules\Accounting\DTOs;

use App\Modules\Accounting\Http\Requests\UpdateChartAccountRequest;

class UpdateChartAccountDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $description,
        public readonly int $accountingStandardId,
        public readonly int $typesPlanId,
        public readonly bool $cecoPermission,
    ) {
    }

    public static function fromRequest(UpdateChartAccountRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            code: $request->string('code')->toString(),
            description: $request->filled('description') ? $request->string('description')->toString() : null,
            accountingStandardId: (int) $request->integer('accounting_standard_id'),
            typesPlanId: (int) $request->integer('types_plan_id'),
            cecoPermission: $request->boolean('ceco_permission'),
        );
    }
}
