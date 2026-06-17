<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountingEntryPositionRequest extends FormRequest
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
            'accounting_document_id' => ['required', 'integer', 'exists:accounting_document,id'],
            'accounting_entry_header_id' => ['required', 'integer', 'exists:accounting_entry_header,id'],
            'accounting_accounts_id' => ['required', 'integer', 'exists:accounting_accounts,id'],
            'id_tercero' => ['nullable', 'integer', 'min:0'],
            'indicator_dc' => ['nullable', 'in:Debito,Credito'],
            'amount' => ['required', 'numeric'],
            'coin_id' => ['required', 'integer', 'exists:coins,id'],
            'cost_center_id' => ['nullable', 'integer', 'exists:cost_center,id'],
            'position_text' => ['nullable', 'string', 'max:255'],
        ];
    }
}
