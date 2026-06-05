<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'force.json' => \App\Shared\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn () => true);

        $exceptions->render(function (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $exception->errors(),
            ], $exception->status);
        });

        $exceptions->render(function (AuthenticationException $_exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors' => [],
            ], 401);
        });

        $exceptions->render(function (NotFoundHttpException $_exception) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'errors' => [],
            ], 404);
        });

        $exceptions->render(function (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $exception->getMessage() : 'Server error.',
                'errors' => config('app.debug')
                    ? ['exception' => class_basename($exception)]
                    : [],
            ], 500);
        });
    })->create();
