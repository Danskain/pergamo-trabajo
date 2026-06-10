<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessStructureRequest extends FormRequest
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
            'country_id' => ['required', 'integer', 'exists:country,id'],
            'coin_id' => ['required', 'integer', 'exists:coins,id'],
            'enterprise_id' => ['required', 'integer', 'exists:enterprises,id'],
            'exercise_variation_id' => ['required', 'integer', 'exists:exercise_variations,id'],
            'chart_account_id' => ['required', 'integer', 'exists:chart_accounts,id'],
        ];
    }
}
