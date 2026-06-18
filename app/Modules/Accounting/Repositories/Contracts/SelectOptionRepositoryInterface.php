<?php

namespace App\Modules\Accounting\Repositories\Contracts;

use App\Modules\Accounting\DTOs\GetSelectOptionsDTO;

interface SelectOptionRepositoryInterface
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function getOptions(GetSelectOptionsDTO $dto): array;
}
