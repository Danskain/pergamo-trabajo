<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountingSchemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'business_structure_id' => ['required', 'integer', 'exists:business_structure,id'],
            'chart_account_id' => ['required', 'integer', 'exists:chart_accounts,id'],
            'assessment_class' => ['required', 'string', 'max:255'],
            'type_movement_id' => ['required', 'integer', 'exists:product_inventory_movements,id'],
            'accounting_event_id' => ['required', 'integer', 'exists:accounting_events,id'],
            'key_operation_id' => ['required', 'integer', 'exists:key_operations,id'],
            'accounting_account_id' => ['required', 'integer', 'exists:accounting_accounts,id'],
            'accounting_nature_id' => ['required', 'integer', 'exists:accounting_nature,id'],
            'require_coce' => ['required', 'boolean'],
        ];
    }
}
