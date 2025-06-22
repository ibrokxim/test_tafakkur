<?php

namespace App\Services;

use App\Models\User;
use App\Data\RegisterData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerUser(RegisterData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }


    public function login(array $credentials): ?string
    {
        if (! $token = Auth::attempt($credentials)) {
            return null;
        }
        return $token;
    }


    public function logout(): void
    {
        Auth::logout();
    }

    public function refreshToken(): string
    {
        return Auth::refresh();
    }

    public function createTokenResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ];
    }
}
