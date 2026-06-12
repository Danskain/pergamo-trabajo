<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCostCenterDTO
{
    public function __construct(
        public readonly int $businessStructureId,
        public readonly int $campusId,
        public readonly string $code,
        public readonly string $name,
        public readonly string $description,
        public readonly int $costCenterTypeId,
        public readonly int $costCenterClassId,
        public readonly int $costCenterNatureId,
        public readonly bool $allowsAllocation,
        public readonly bool $distributesCosts,
        public readonly bool $functionalUnit,
        public readonly bool $profitCenter,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            businessStructureId: (int) $request->integer('business_structure_id'),
            campusId: (int) $request->integer('campus_id'),
            code: $request->string('code')->toString(),
            name: $request->string('name')->toString(),
            description: $request->string('description')->toString(),
            costCenterTypeId: (int) $request->integer('cost_center_type_id'),
            costCenterClassId: (int) $request->integer('cost_center_class_id'),
            costCenterNatureId: (int) $request->integer('cost_center_nature_id'),
            allowsAllocation: $request->boolean('allows_allocation'),
            distributesCosts: $request->boolean('distributes_costs'),
            functionalUnit: $request->boolean('functional_unit'),
            profitCenter: $request->boolean('profit_center'),
        );
    }
}
