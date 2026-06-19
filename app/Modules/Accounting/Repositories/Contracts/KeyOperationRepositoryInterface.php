<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateKeyOperationDTO;
use App\Modules\Accounting\DTOs\UpdateKeyOperationDTO;
use App\Modules\Accounting\Models\KeyOperation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface KeyOperationRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): KeyOperation;

    public function findTrashedOrFail(int $id): KeyOperation;

    public function create(CreateKeyOperationDTO $dto): KeyOperation;

    public function update(KeyOperation $keyOperation, UpdateKeyOperationDTO $dto): KeyOperation;

    public function delete(KeyOperation $keyOperation): void;

    public function restore(KeyOperation $keyOperation): KeyOperation;
}
