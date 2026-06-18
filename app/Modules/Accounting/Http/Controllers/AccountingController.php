<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\GetSelectOptionsDTO;
use App\Modules\Accounting\Http\Requests\GetSelectOptionsRequest;
use App\Modules\Accounting\Services\SelectOptionService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingController extends ApiController
{
    public function __construct(
        protected SelectOptionService $selectOptionService,
    ) {
    }

    public function health(): JsonResponse
    {
        return $this->successResponse([
            'module' => 'accounting',
            'status' => 'ok',
        ], 'Accounting module is available.');
    }

    public function selectOptions(GetSelectOptionsRequest $request): JsonResponse
    {
        $dto = GetSelectOptionsDTO::fromRequest($request);
        $options = $this->selectOptionService->getOptions($dto);

        return $this->successResponse(
            $options,
            'Select options retrieved successfully.',
            200,
            [
                'catalogs' => $dto->catalogs,
                'enriched_labels' => $dto->enrichedLabels,
            ],
        );
    }
}
