<?php

namespace App\Http\Controllers\Api;

use App\Data\RegisterData;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function register(RegisterData $registerData): JsonResponse
    {
        $user = $this->authService->registerUser($registerData);
        $token = Auth::login($user);

        return response()->json($this->authService->createTokenResponse($token), 201);
    }


    public function login(Request $request): JsonResponse
    {
        // Для логина DTO не так удобен, поэтому валидируем напрямую
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $token = $this->authService->login($credentials);

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($this->authService->createTokenResponse($token));
    }


    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        $token = $this->authService->refreshToken();
        return response()->json($this->authService->createTokenResponse($token));
    }


    public function me(): JsonResponse
    {
        return response()->json(Auth::user());
    }
}
