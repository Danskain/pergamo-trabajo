<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateReferenceDTO;
use App\Modules\Accounting\DTOs\UpdateReferenceDTO;
use App\Modules\Accounting\Models\Reference;
use App\Modules\Accounting\Repositories\Contracts\ReferenceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentReferenceRepository implements ReferenceRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return Reference::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): Reference
    {
        return Reference::query()->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): Reference
    {
        return Reference::query()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function create(CreateReferenceDTO $dto): Reference
    {
        return Reference::query()->create([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);
    }

    public function update(Reference $reference, UpdateReferenceDTO $dto): Reference
    {
        $reference->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
        ]);

        return $reference->refresh();
    }

    public function delete(Reference $reference): void
    {
        $reference->delete();
    }

    public function restore(Reference $reference): Reference
    {
        $reference->restore();

        return $reference->refresh();
    }
}
