<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateReferenceDTO;
use App\Modules\Accounting\DTOs\UpdateReferenceDTO;
use App\Modules\Accounting\Models\Reference;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReferenceRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): Reference;

    public function findTrashedOrFail(int $id): Reference;

    public function create(CreateReferenceDTO $dto): Reference;

    public function update(Reference $reference, UpdateReferenceDTO $dto): Reference;

    public function delete(Reference $reference): void;

    public function restore(Reference $reference): Reference;
}
