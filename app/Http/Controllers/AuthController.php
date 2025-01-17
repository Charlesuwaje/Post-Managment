<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct(public readonly AuthService $authService) {}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role'  => 'required|string|exists:roles,id',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return $this->authService->register($validated);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        return $this->authService->login($validated);
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|exists:users,email',
        ]);

        return $this->authService->forgotPassword($validated);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $request->only('email', 'token', 'password', 'password_confirmation');
        $status = $this->authService->resetPassword($data);

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset successfully'], 200);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'token' => $newToken,
                'message' => 'Token refreshed successfully.'
            ], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token not provided'], 400);
        }
    }
}
