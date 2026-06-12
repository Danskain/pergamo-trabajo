<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\CreateDocumentSourceTypeDTO;
use App\Modules\Accounting\DTOs\UpdateDocumentSourceTypeDTO;
use App\Modules\Accounting\Models\DocumentSourceType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DocumentSourceTypeRepositoryInterface
{
    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findOrFail(int $id): DocumentSourceType;

    public function findTrashedOrFail(int $id): DocumentSourceType;

    public function create(CreateDocumentSourceTypeDTO $dto): DocumentSourceType;

    public function update(DocumentSourceType $documentSourceType, UpdateDocumentSourceTypeDTO $dto): DocumentSourceType;

    public function delete(DocumentSourceType $documentSourceType): void;

    public function restore(DocumentSourceType $documentSourceType): DocumentSourceType;
}
