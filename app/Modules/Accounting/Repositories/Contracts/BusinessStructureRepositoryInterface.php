<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateBusinessStructureDTO;
use App\Modules\Accounting\DTOs\UpdateBusinessStructureDTO;
use App\Modules\Accounting\Models\BusinessStructure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BusinessStructureRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): BusinessStructure;

    public function findTrashedOrFail(int $id): BusinessStructure;

    public function create(CreateBusinessStructureDTO $dto): BusinessStructure;

    public function update(BusinessStructure $businessStructure, UpdateBusinessStructureDTO $dto): BusinessStructure;

    public function delete(BusinessStructure $businessStructure): void;

    public function restore(BusinessStructure $businessStructure): BusinessStructure;
}
