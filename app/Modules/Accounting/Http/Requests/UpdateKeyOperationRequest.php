<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeyOperationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'module_id' => ['required', 'integer', 'exists:modules,id'],
            'accounting_nature_id' => ['required', 'integer', 'exists:accounting_nature,id'],
            'affects_taxes' => ['required', 'boolean'],
        ];
    }
}
