<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateCostCenterTypeDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly string $description,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            code: $request->string('code')->toString(),
            description: $request->string('description')->toString(),
        );
    }
}
