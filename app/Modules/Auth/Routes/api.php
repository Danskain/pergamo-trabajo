<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('authentication.route_prefix', 'api/v1/auth'))
    ->middleware(config('authentication.middleware', ['api', 'force.json']))
    ->name('auth.')
    ->group(function (): void {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });
