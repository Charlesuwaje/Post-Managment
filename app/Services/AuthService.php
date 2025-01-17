<?php

namespace App\Services;

use App\Models\User;
use App\Mail\ForgetpasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
// use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Password;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{

    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    public function generateOtp(string $email, string $fullName): void
    {
        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $otp, 'created_at' => now()]
        );

        Mail::to($email)->send(new ForgetpasswordMail($otp, $fullName));
    }
    public function verifyOtp(string $otp)
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $otp)
            ->first();

        if ($passwordReset) {
            $createdAt = \Carbon\Carbon::parse($passwordReset->created_at);
            if ($createdAt->addMinutes(5)->isPast()) {
                return false;
            }

            return true;
        }

        return false;
    }
    public function resetPassword(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        $user->password = Hash::make($password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return $user;
    }
}
