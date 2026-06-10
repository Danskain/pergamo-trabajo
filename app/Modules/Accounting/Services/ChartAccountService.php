<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateChartAccountDTO;
use App\Modules\Accounting\DTOs\UpdateChartAccountDTO;
use App\Modules\Accounting\Models\ChartAccount;
use App\Modules\Accounting\Repositories\Contracts\ChartAccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ChartAccountService
{
    public function __construct(
        protected ChartAccountRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): ChartAccount
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): ChartAccount
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateChartAccountDTO $dto): ChartAccount
    {
        return $this->repository->create($dto);
    }

    public function update(ChartAccount $chartAccount, UpdateChartAccountDTO $dto): ChartAccount
    {
        return $this->repository->update($chartAccount, $dto);
    }

    public function delete(ChartAccount $chartAccount): void
    {
        $this->repository->delete($chartAccount);
    }

    public function restore(ChartAccount $chartAccount): ChartAccount
    {
        return $this->repository->restore($chartAccount);
    }
}
