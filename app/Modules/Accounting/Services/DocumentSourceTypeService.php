<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateDocumentSourceTypeDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceTypeDTO;
use App\Modules\Accounting\Models\DocumentSourceType;
use App\Modules\Accounting\Repositories\Contracts\DocumentSourceTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentSourceTypeService
{
    public function __construct(
        protected DocumentSourceTypeRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): DocumentSourceType
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): DocumentSourceType
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateDocumentSourceTypeDTO $dto): DocumentSourceType
    {
        return $this->repository->create($dto);
    }

    public function update(DocumentSourceType $documentSourceType, UpdateDocumentSourceTypeDTO $dto): DocumentSourceType
    {
        return $this->repository->update($documentSourceType, $dto);
    }

    public function delete(DocumentSourceType $documentSourceType): void
    {
        $this->repository->delete($documentSourceType);
    }

    public function restore(DocumentSourceType $documentSourceType): DocumentSourceType
    {
        return $this->repository->restore($documentSourceType);
    }
}
