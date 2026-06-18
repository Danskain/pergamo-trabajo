<?php

namespace App\Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetSelectOptionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $routeCatalog = $this->route('catalog');
        $catalogs = $this->input('catalogs');

        if (is_string($catalogs)) {
            $catalogs = array_values(array_filter(array_map('trim', explode(',', $catalogs))));
        }

        if (($catalogs === null || $catalogs === []) && filled($routeCatalog)) {
            $catalogs = [$routeCatalog];
        }

        $enrichedLabels = $this->input('enriched_labels');

        if (is_string($enrichedLabels)) {
            $enrichedLabels = filter_var($enrichedLabels, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }

        $this->merge([
            'catalog' => $this->route('catalog'),
            'catalogs' => $catalogs,
            'enriched_labels' => $enrichedLabels,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        $catalogs = array_keys(config('accounting.select_options.catalogs', []));

        return [
            'catalog' => ['nullable', 'string', Rule::in($catalogs)],
            'catalogs' => ['required', 'array', 'min:1'],
            'catalogs.*' => ['required', 'string', Rule::in($catalogs)],
            'search' => ['nullable', 'string', 'max:255'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'enriched_labels' => ['nullable', 'boolean'],
        ];
    }
}
