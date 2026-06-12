<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\CreateDocumentSourceDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceDTO;
use App\Modules\Accounting\Models\DocumentSource;
use App\Modules\Accounting\Repositories\Contracts\DocumentSourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentSourceService
{
    public function __construct(
        protected DocumentSourceRepositoryInterface $repository,
    ) {
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $page);
    }

    public function findOrFail(int $id): DocumentSource
    {
        return $this->repository->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): DocumentSource
    {
        return $this->repository->findTrashedOrFail($id);
    }

    public function create(CreateDocumentSourceDTO $dto): DocumentSource
    {
        return $this->repository->create($dto);
    }

    public function update(DocumentSource $documentSource, UpdateDocumentSourceDTO $dto): DocumentSource
    {
        return $this->repository->update($documentSource, $dto);
    }

    public function delete(DocumentSource $documentSource): void
    {
        $this->repository->delete($documentSource);
    }

    public function restore(DocumentSource $documentSource): DocumentSource
    {
        return $this->repository->restore($documentSource);
    }
}
