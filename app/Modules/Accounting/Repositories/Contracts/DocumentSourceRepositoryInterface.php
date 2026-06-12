<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateDocumentSourceDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceDTO;
use App\Modules\Accounting\Models\DocumentSource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DocumentSourceRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): DocumentSource;

    public function findTrashedOrFail(int $id): DocumentSource;

    public function create(CreateDocumentSourceDTO $dto): DocumentSource;

    public function update(DocumentSource $documentSource, UpdateDocumentSourceDTO $dto): DocumentSource;

    public function delete(DocumentSource $documentSource): void;

    public function restore(DocumentSource $documentSource): DocumentSource;
}
