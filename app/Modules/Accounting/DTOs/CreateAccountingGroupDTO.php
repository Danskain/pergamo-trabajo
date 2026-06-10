<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountingGroupDTO
{
    public function __construct(
        public readonly string $code,
        public readonly int $accountClassId,
        public readonly string $name,
        public readonly string $description,
        public readonly int $accountFrom,
        public readonly int $accountTo,
        public readonly bool $affectsClosing,
        public readonly bool $affectsFinancialStatements,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            code: $request->string('code')->toString(),
            accountClassId: (int) $request->integer('account_class_id'),
            name: $request->string('name')->toString(),
            description: $request->string('description')->toString(),
            accountFrom: (int) $request->integer('account_from'),
            accountTo: (int) $request->integer('account_to'),
            affectsClosing: $request->boolean('affects_closing'),
            affectsFinancialStatements: $request->boolean('affects_financial_statements'),
        );
    }
}
