<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCostCenterRequest extends FormRequest
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
            'business_structure_id' => ['required', 'integer', 'exists:business_structure,id'],
            'campus_id' => ['required', 'integer', 'exists:campus,id'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cost_center_type_id' => ['required', 'integer', 'exists:cost_center_type,id'],
            'cost_center_class_id' => ['required', 'integer', 'exists:cost_center_class,id'],
            'cost_center_nature_id' => ['required', 'integer', 'exists:cost_center_nature,id'],
            'allows_allocation' => ['required', 'boolean'],
            'distributes_costs' => ['required', 'boolean'],
            'functional_unit' => ['required', 'boolean'],
            'profit_center' => ['required', 'boolean'],
        ];
    }
}
