<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateBusinessStructureDTO;
use App\Modules\Accounting\DTOs\UpdateBusinessStructureDTO;
use App\Modules\Accounting\Models\BusinessStructure;
use App\Modules\Accounting\Repositories\Contracts\BusinessStructureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentBusinessStructureRepository implements BusinessStructureRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return BusinessStructure::query()
            ->with(['exerciseVariation', 'chartAccount'])
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): BusinessStructure
    {
        return BusinessStructure::query()
            ->with(['exerciseVariation', 'chartAccount'])
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): BusinessStructure
    {
        return BusinessStructure::query()
            ->withTrashed()
            ->with(['exerciseVariation', 'chartAccount'])
            ->findOrFail($id);
    }

    public function create(CreateBusinessStructureDTO $dto): BusinessStructure
    {
        $businessStructure = BusinessStructure::query()->create([
            'country_id' => $dto->countryId,
            'coin_id' => $dto->coinId,
            'enterprise_id' => $dto->enterpriseId,
            'exercise_variation_id' => $dto->exerciseVariationId,
            'chart_account_id' => $dto->chartAccountId,
        ]);

        return $businessStructure->load(['exerciseVariation', 'chartAccount']);
    }

    public function update(BusinessStructure $businessStructure, UpdateBusinessStructureDTO $dto): BusinessStructure
    {
        $businessStructure->update([
            'country_id' => $dto->countryId,
            'coin_id' => $dto->coinId,
            'enterprise_id' => $dto->enterpriseId,
            'exercise_variation_id' => $dto->exerciseVariationId,
            'chart_account_id' => $dto->chartAccountId,
        ]);

        return $businessStructure->load(['exerciseVariation', 'chartAccount']);
    }

    public function delete(BusinessStructure $businessStructure): void
    {
        $businessStructure->delete();
    }

    public function restore(BusinessStructure $businessStructure): BusinessStructure
    {
        $businessStructure->restore();

        return $businessStructure->load(['exerciseVariation', 'chartAccount']);
    }
}
