<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class GetSelectOptionsDTO
{
    public function __construct(
        /** @var array<int, string> */
        public readonly array $catalogs,
        public readonly ?string $search,
        public readonly int $limit,
        public readonly bool $enrichedLabels,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        $catalogs = $request->input('catalogs', []);

        if (is_string($catalogs)) {
            $catalogs = array_filter(array_map('trim', explode(',', $catalogs)));
        }

        return new self(
            catalogs: array_values(array_unique(array_map('strval', is_array($catalogs) ? $catalogs : []))),
            search: $request->filled('search') ? $request->string('search')->toString() : null,
            limit: max(1, min(100, (int) $request->integer('limit', 50))),
            enrichedLabels: $request->boolean('enriched_labels'),
        );
    }

    public function isMultiple(): bool
    {
        return count($this->catalogs) > 1;
    }
}
