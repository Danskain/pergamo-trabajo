<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiTokenAuthService
{
    /**
     * @param array<string, string> $credentials
     * @return array{0: User, 1: string}
     */
    public function attempt(array $credentials): array
    {
        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $plainTextToken = Str::random(60);

        $user->forceFill([
            'api_token' => hash('sha256', $plainTextToken),
        ])->save();

        return [$user, $plainTextToken];
    }

    /**
     * @return array<string, mixed>
     */
    public function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
