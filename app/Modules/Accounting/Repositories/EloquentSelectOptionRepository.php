<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\GetSelectOptionsDTO;
use App\Modules\Accounting\Models\AccountClass;
use App\Modules\Accounting\Models\BusinessStructure;
use App\Modules\Accounting\Models\DocumentSource;
use App\Modules\Accounting\Repositories\Contracts\SelectOptionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EloquentSelectOptionRepository implements SelectOptionRepositoryInterface
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function getOptions(GetSelectOptionsDTO $dto): array
    {
        $result = [];

        foreach ($dto->catalogs as $catalogKey) {
            /** @var array<string, mixed> $catalog */
            $catalog = config("accounting.select_options.catalogs.{$catalogKey}", []);
            /** @var class-string<Model> $modelClass */
            $modelClass = $catalog['model'];
            /** @var array<int, string> $labelFields */
            $labelFields = $catalog['label_fields'] ?? ['id'];
            /** @var array<int, string> $metaFields */
            $metaFields = $catalog['meta_fields'] ?? [];
            /** @var array<int, string> $searchFields */
            $searchFields = $catalog['search_fields'] ?? $labelFields;
            /** @var array<int, string> $enrichedWith */
            $enrichedWith = $catalog['enriched_with'] ?? [];
            $valueField = (string) ($catalog['value_field'] ?? 'id');
            $orderBy = (string) ($catalog['order_by'] ?? $valueField);

            $selectFields = array_values(array_unique(array_merge([$valueField], $labelFields, $metaFields, $searchFields)));

            $query = $modelClass::query()
                ->select($selectFields)
                ->orderBy($orderBy)
                ->limit($dto->limit);

            if ($dto->enrichedLabels && $enrichedWith !== []) {
                $query->with($enrichedWith);
            }

            if ($dto->search !== null && $dto->search !== '') {
                $search = $dto->search;

                $query->where(function (Builder $builder) use ($searchFields, $search): void {
                    foreach ($searchFields as $index => $field) {
                        if ($index === 0) {
                            $builder->where($field, 'like', "%{$search}%");
                            continue;
                        }

                        $builder->orWhere($field, 'like', "%{$search}%");
                    }
                });
            }

            $result[$catalogKey] = $query->get()
                ->map(function (Model $item) use ($catalogKey, $dto, $valueField, $labelFields, $metaFields): array {
                    return [
                        'value' => $item->{$valueField},
                        'label' => $this->buildLabel($catalogKey, $item, $labelFields, $valueField, $dto->enrichedLabels),
                        'meta' => collect($metaFields)
                            ->mapWithKeys(fn (string $field): array => [$field => $item->{$field}])
                            ->all(),
                    ];
                })
                ->values()
                ->all();
        }

        return $result;
    }

    /**
     * @param array<int, string> $labelFields
     */
    private function buildLabel(
        string $catalogKey,
        Model $item,
        array $labelFields,
        string $valueField,
        bool $enrichedLabels,
    ): string {
        if ($enrichedLabels) {
            $enrichedLabel = $this->buildEnrichedLabel($catalogKey, $item);

            if ($enrichedLabel !== null) {
                return $enrichedLabel;
            }
        }

        $labelParts = collect($labelFields)
            ->map(fn (string $field): ?string => filled($item->{$field}) ? (string) $item->{$field} : null)
            ->filter()
            ->values();

        return $labelParts->isNotEmpty()
            ? $labelParts->implode(' - ')
            : (string) $item->{$valueField};
    }

    private function buildEnrichedLabel(string $catalogKey, Model $item): ?string
    {
        return match ($catalogKey) {
            'account_class' => $item instanceof AccountClass ? $this->buildAccountClassLabel($item) : null,
            'business_structure' => $item instanceof BusinessStructure ? $this->buildBusinessStructureLabel($item) : null,
            'documents_source' => $item instanceof DocumentSource ? $this->buildDocumentSourceLabel($item) : null,
            default => null,
        };
    }

    private function buildAccountClassLabel(AccountClass $accountClass): ?string
    {
        $parts = collect([
            $accountClass->name,
            $accountClass->accountingNature?->code,
            $accountClass->accountingNature?->name,
        ])->filter()->values();

        return $parts->isNotEmpty() ? $parts->implode(' - ') : null;
    }

    private function buildBusinessStructureLabel(BusinessStructure $businessStructure): ?string
    {
        $parts = collect([
            $businessStructure->enterprise?->name,
            $businessStructure->country?->name,
            $businessStructure->coin?->alphabetic_code,
            $businessStructure->exerciseVariation?->name,
        ])->filter()->values();

        return $parts->isNotEmpty() ? $parts->implode(' - ') : null;
    }

    private function buildDocumentSourceLabel(DocumentSource $documentSource): ?string
    {
        $parts = collect([
            $documentSource->documentSourceType?->code,
            $documentSource->number_document_source,
            $documentSource->reference?->name,
            $documentSource->exercise,
        ])->filter()->values();

        return $parts->isNotEmpty() ? $parts->implode(' - ') : null;
    }
}
