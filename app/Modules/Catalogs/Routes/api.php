<?php

use App\Modules\Catalogs\Http\Controllers\CatalogsController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('catalogs.route_prefix', 'api/v1/catalogs'))
    ->middleware(config('catalogs.middleware', ['api', 'force.json']))
    ->name('catalogs.')
    ->group(function (): void {
        Route::get('months', [CatalogsController::class, 'months'])->name('months.index');
    });
