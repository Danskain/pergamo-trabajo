<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateAccountClassDTO;
use App\Modules\Accounting\DTOs\UpdateAccountClassDTO;
use App\Modules\Accounting\Models\AccountClass;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountClassRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): AccountClass;

    public function findTrashedOrFail(int $id): AccountClass;

    public function create(CreateAccountClassDTO $dto): AccountClass;

    public function update(AccountClass $accountClass, UpdateAccountClassDTO $dto): AccountClass;

    public function delete(AccountClass $accountClass): void;

    public function restore(AccountClass $accountClass): AccountClass;
}
