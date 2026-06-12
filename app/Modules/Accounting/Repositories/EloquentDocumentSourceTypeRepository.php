<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateDocumentSourceTypeDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceTypeDTO;
use App\Modules\Accounting\Models\DocumentSourceType;
use App\Modules\Accounting\Repositories\Contracts\DocumentSourceTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentDocumentSourceTypeRepository implements DocumentSourceTypeRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return DocumentSourceType::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): DocumentSourceType
    {
        return DocumentSourceType::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): DocumentSourceType
    {
        return DocumentSourceType::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateDocumentSourceTypeDTO $dto): DocumentSourceType
    {
        return DocumentSourceType::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'generates_accounting' => $dto->generatesAccounting,
            'manual_entry' => $dto->manualEntry,
            'requires_approval' => $dto->requiresApproval,
            'requires_third' => $dto->requiresThird,
            'requires_ceco' => $dto->requiresCeco,
            'affects_inventory' => $dto->affectsInventory,
            'affects_cartera' => $dto->affectsCartera,
            'affects_cxp' => $dto->affectsCxp,
            'affects_treasury' => $dto->affectsTreasury,
            'allows_reversal' => $dto->allowsReversal,
        ]);
    }

    public function update(DocumentSourceType $documentSourceType, UpdateDocumentSourceTypeDTO $dto): DocumentSourceType
    {
        $documentSourceType->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'generates_accounting' => $dto->generatesAccounting,
            'manual_entry' => $dto->manualEntry,
            'requires_approval' => $dto->requiresApproval,
            'requires_third' => $dto->requiresThird,
            'requires_ceco' => $dto->requiresCeco,
            'affects_inventory' => $dto->affectsInventory,
            'affects_cartera' => $dto->affectsCartera,
            'affects_cxp' => $dto->affectsCxp,
            'affects_treasury' => $dto->affectsTreasury,
            'allows_reversal' => $dto->allowsReversal,
        ]);

        return $documentSourceType->refresh();
    }

    public function delete(DocumentSourceType $documentSourceType): void
    {
        $documentSourceType->delete();
    }

    public function restore(DocumentSourceType $documentSourceType): DocumentSourceType
    {
        $documentSourceType->restore();

        return $documentSourceType->refresh();
    }
}
