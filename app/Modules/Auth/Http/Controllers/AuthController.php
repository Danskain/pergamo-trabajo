<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Http\Requests\LoginRequest;
use App\Modules\Auth\Services\ApiTokenAuthService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function __construct(
        protected ApiTokenAuthService $service,
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        [$user, $token] = $this->service->attempt($request->validated());

        return $this->successResponse([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->service->serializeUser($user),
        ], 'Authenticated successfully.');
    }
}
