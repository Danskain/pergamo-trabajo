<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateTypeAccountDTO;
use App\Modules\Accounting\DTOs\UpdateTypeAccountDTO;
use App\Modules\Accounting\Models\TypeAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TypeAccountRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): TypeAccount;

    public function findTrashedOrFail(int $id): TypeAccount;

    public function create(CreateTypeAccountDTO $dto): TypeAccount;

    public function update(TypeAccount $typeAccount, UpdateTypeAccountDTO $dto): TypeAccount;

    public function delete(TypeAccount $typeAccount): void;

    public function restore(TypeAccount $typeAccount): TypeAccount;
}
