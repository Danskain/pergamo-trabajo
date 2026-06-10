<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountingAccountDTO
{
    public function __construct(
        public readonly string $account,
        public readonly int $chartAccountId,
        public readonly string $name,
        public readonly int $accountClassId,
        public readonly int $typesAccountId,
        public readonly int $accountingGroupId,
        public readonly bool $allowsManualTransactions,
        public readonly bool $associatedAccount,
        public readonly bool $acceptsTaxes,
        public readonly bool $foreignCurrency,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            account: $request->string('account')->toString(),
            chartAccountId: (int) $request->integer('chart_account_id'),
            name: $request->string('name')->toString(),
            accountClassId: (int) $request->integer('account_class_id'),
            typesAccountId: (int) $request->integer('types_account_id'),
            accountingGroupId: (int) $request->integer('accounting_group_id'),
            allowsManualTransactions: $request->boolean('allows_manual_transactions'),
            associatedAccount: $request->boolean('associated_account'),
            acceptsTaxes: $request->boolean('accepts_taxes'),
            foreignCurrency: $request->boolean('foreign_currency'),
        );
    }
}
