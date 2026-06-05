<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingController extends ApiController
{
    public function health(): JsonResponse
    {
        return $this->successResponse([
            'module' => 'accounting',
            'status' => 'ok',
        ], 'Accounting module is available.');
    }
}
