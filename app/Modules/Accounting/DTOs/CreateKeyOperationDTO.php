<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateKeyOperationDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly int $moduleId,
        public readonly int $accountingNatureId,
        public readonly bool $affectsTaxes,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            code: $request->string('code')->toString(),
            name: $request->string('name')->toString(),
            moduleId: $request->integer('module_id'),
            accountingNatureId: $request->integer('accounting_nature_id'),
            affectsTaxes: $request->boolean('affects_taxes'),
        );
    }
}
