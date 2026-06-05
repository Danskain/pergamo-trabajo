<?php

namespace App\Shared\Responses;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function successResponse(
        mixed $data = null,
        string $message = 'Request completed successfully.',
        int $status = 200,
        array $meta = [],
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    public function errorResponse(
        string $message = 'Request failed.',
        int $status = 400,
        array $errors = [],
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
