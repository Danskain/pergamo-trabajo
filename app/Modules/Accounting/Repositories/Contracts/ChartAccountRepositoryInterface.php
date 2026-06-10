<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateChartAccountDTO;
use App\Modules\Accounting\DTOs\UpdateChartAccountDTO;
use App\Modules\Accounting\Models\ChartAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ChartAccountRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): ChartAccount;

    public function findTrashedOrFail(int $id): ChartAccount;

    public function create(CreateChartAccountDTO $dto): ChartAccount;

    public function update(ChartAccount $chartAccount, UpdateChartAccountDTO $dto): ChartAccount;

    public function delete(ChartAccount $chartAccount): void;

    public function restore(ChartAccount $chartAccount): ChartAccount;
}
