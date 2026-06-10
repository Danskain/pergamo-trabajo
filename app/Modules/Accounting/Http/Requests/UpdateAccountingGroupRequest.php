<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountingGroupRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:255'],
            'account_class_id' => ['required', 'integer', 'exists:account_class,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'account_from' => ['required', 'integer', 'min:0'],
            'account_to' => ['required', 'integer', 'min:0'],
            'affects_closing' => ['required', 'boolean'],
            'affects_financial_statements' => ['required', 'boolean'],
        ];
    }
}
