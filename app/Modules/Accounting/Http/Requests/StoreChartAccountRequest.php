<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChartAccountRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'accounting_standard_id' => ['required', 'integer', 'exists:accounting_standard,id'],
            'types_plan_id' => ['required', 'integer', 'exists:types_plans,id'],
            'ceco_permission' => ['required', 'boolean'],
        ];
    }
}
