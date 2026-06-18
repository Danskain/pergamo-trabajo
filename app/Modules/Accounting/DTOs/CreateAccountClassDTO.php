<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountClassDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $accountingNatureId,
        public readonly string $description,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            accountingNatureId: $request->integer('accounting_nature_id'),
            description: $request->string('description')->toString(),
        );
    }
}
