<?php

namespace App\Modules\Catalogs\Http\Controllers;

use App\Modules\Catalogs\Http\Resources\MonthResource;
use App\Modules\Catalogs\Services\MonthService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CatalogsController extends ApiController
{
    public function __construct(
        protected MonthService $monthService,
    ) {
    }

    public function months(): JsonResponse
    {
        $months = MonthResource::collection($this->monthService->all())->resolve();

        return $this->successResponse($months, 'Months retrieved successfully.');
    }
}
