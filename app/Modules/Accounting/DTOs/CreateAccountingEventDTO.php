<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountingEventDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly int $accountingMomentId,
        public readonly string $origin,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            code: $request->string('code')->toString(),
            name: $request->string('name')->toString(),
            accountingMomentId: $request->integer('accounting_moment_id'),
            origin: $request->string('origin')->toString(),
        );
    }
}
