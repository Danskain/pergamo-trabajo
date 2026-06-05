<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseVariationRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'start_exercise' => ['required', 'integer', 'exists:months,id'],
            'end_exercise' => ['required', 'integer', 'exists:months,id'],
            'normal_periods' => ['required', 'integer', 'min:0'],
            'special_periods' => ['required', 'integer', 'min:0'],
            'calendar_dependent' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
