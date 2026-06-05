<?php

namespace App\Modules\Accounting\DTOs;

use App\Modules\Accounting\Http\Requests\UpdateAccountingStandardRequest;

class UpdateAccountingStandardDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $description,
    ) {
    }

    public static function fromRequest(UpdateAccountingStandardRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            code: $request->string('code')->toString(),
            description: $request->filled('description') ? $request->string('description')->toString() : null,
        );
    }
}
