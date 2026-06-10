<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountingAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account' => ['required', 'string', 'max:255'],
            'chart_account_id' => ['required', 'integer', 'exists:chart_accounts,id'],
            'name' => ['required', 'string', 'max:255'],
            'account_class_id' => ['required', 'integer', 'exists:account_class,id'],
            'types_account_id' => ['required', 'integer', 'exists:types_accounts,id'],
            'accounting_group_id' => ['required', 'integer', 'exists:accounting_groups,id'],
            'allows_manual_transactions' => ['required', 'boolean'],
            'associated_account' => ['required', 'boolean'],
            'accepts_taxes' => ['required', 'boolean'],
            'foreign_currency' => ['required', 'boolean'],
        ];
    }
}
