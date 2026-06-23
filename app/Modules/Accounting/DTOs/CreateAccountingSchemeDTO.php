<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountingSchemeDTO
{
    public function __construct(
        public readonly int $businessStructureId,
        public readonly int $chartAccountId,
        public readonly string $assessmentClass,
        public readonly int $typeMovementId,
        public readonly int $accountingEventId,
        public readonly int $keyOperationId,
        public readonly int $accountingAccountId,
        public readonly int $accountingNatureId,
        public readonly bool $requireCoce,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            businessStructureId: $request->integer('business_structure_id'),
            chartAccountId: $request->integer('chart_account_id'),
            assessmentClass: $request->string('assessment_class')->toString(),
            typeMovementId: $request->integer('type_movement_id'),
            accountingEventId: $request->integer('accounting_event_id'),
            keyOperationId: $request->integer('key_operation_id'),
            accountingAccountId: $request->integer('accounting_account_id'),
            accountingNatureId: $request->integer('accounting_nature_id'),
            requireCoce: $request->boolean('require_coce'),
        );
    }
}
