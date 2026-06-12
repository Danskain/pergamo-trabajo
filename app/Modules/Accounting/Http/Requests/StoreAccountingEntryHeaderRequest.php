<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountingEntryHeaderRequest extends FormRequest
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
            'accounting_period' => ['required', 'integer'],
            'coin_id' => ['required', 'integer', 'exists:coins,id'],
            'description' => ['required', 'string'],
            'total_debits' => ['required', 'numeric'],
            'total_credits' => ['required', 'numeric'],
            'reference_document' => ['required', 'string', 'max:255'],
            'documents_source_id' => ['required', 'integer', 'exists:documents_source,id'],
        ];
    }
}
