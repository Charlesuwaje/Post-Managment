<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
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

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();
        // dd($user);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->authService->generateOtp($request->email, $user->name);

        return response()->json(['message' => 'OTP code sent to your email'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $isOtpValid = $this->authService->verifyOtp($request->otp);

        if ($isOtpValid) {
            return response()->json(['message' => 'OTP code verified successfully'], 200);
        }

        return response()->json(['message' => 'Invalid or expired OTP code'], 400);
    }

    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $this->authService->resetPassword($request->email, $request->password);

        if (!$user) {
            return response()->json(['message' => 'This user is not found'], 404);
        }

        return response()->json([
            'message' => 'Password reset successful',
            'data' => $user,
        ]);
    }
}
