<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\DTOs\GetSelectOptionsDTO;
use App\Modules\Accounting\Repositories\Contracts\SelectOptionRepositoryInterface;

class SelectOptionService
{
    public function __construct(
        protected SelectOptionRepositoryInterface $repository,
    ) {
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>|array<int, array<string, mixed>>
     */
    public function getOptions(GetSelectOptionsDTO $dto): array
    {
        $options = $this->repository->getOptions($dto);

        if ($dto->isMultiple()) {
            return $options;
        }

        $catalog = $dto->catalogs[0] ?? null;

        return $catalog !== null ? ($options[$catalog] ?? []) : [];
    }
}
