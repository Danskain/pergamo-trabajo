<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentSourceTypeRequest extends FormRequest
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
            'description' => ['required', 'string'],
            'generates_accounting' => ['required', 'boolean'],
            'manual_entry' => ['required', 'boolean'],
            'requires_approval' => ['required', 'boolean'],
            'requires_third' => ['required', 'boolean'],
            'requires_ceco' => ['required', 'boolean'],
            'affects_inventory' => ['required', 'boolean'],
            'affects_cartera' => ['required', 'boolean'],
            'affects_cxp' => ['required', 'boolean'],
            'affects_treasury' => ['required', 'boolean'],
            'allows_reversal' => ['required', 'boolean'],
        ];
    }
}
