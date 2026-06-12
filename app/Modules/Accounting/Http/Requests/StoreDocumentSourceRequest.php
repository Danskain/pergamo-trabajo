<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentSourceRequest extends FormRequest
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
            'modules_id' => ['required', 'integer', 'exists:modules,id'],
            'document_source_type_id' => ['required', 'integer', 'exists:document_source_type,id'],
            'number_document_source' => ['required', 'string', 'max:255'],
            'document_date' => ['required', 'date'],
            'accounting_date' => ['required', 'date'],
            'reference_id' => ['required', 'integer', 'exists:reference,id'],
            'total_value' => ['required', 'numeric'],
            'coin_id' => ['required', 'integer', 'exists:coins,id'],
            'financial_statement_id' => ['required', 'integer', 'exists:financial_statements,id'],
            'accounting_document_id' => ['required', 'integer', 'exists:accounting_document,id'],
            'exercise' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ];
    }
}
