<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Регистрация нового пользователя
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'applicant', // По умолчанию соискатель
        ]);

        // Генерация токена для авторизации
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user->only(['id', 'name', 'surname', 'email', 'role']),
            'token' => $token,
        ], 201);
    }

    /**
     * Авторизация пользователя
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Неверные учетные данные'
            ], 401);
        }

        // Отзыв всех предыдущих токенов пользователя
        $user->tokens()->delete();

        // Генерация нового токена
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user->only(['id', 'name', 'surname', 'email', 'role']),
            'token' => $token,
        ]);
    }

    /**
     * Выход из системы (удаление токена)
     */
    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }
}
