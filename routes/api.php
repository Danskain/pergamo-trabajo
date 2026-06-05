<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Pergamo API is available.',
        'data' => [
            'name' => config('app.name'),
            'type' => 'api',
            'versions' => ['v1'],
        ],
    ]);
});

Route::prefix('v1')->middleware(['force.json'])->group(function (): void {
    Route::get('/', function () {
        return response()->json([
            'success' => true,
            'message' => 'Pergamo API v1 is available.',
            'data' => [
                'version' => 'v1',
                'status' => 'ok',
            ],
        ]);
    });
});
