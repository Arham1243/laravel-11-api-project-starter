<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\SignupResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
        ]);
        $user->assignRole('Administrator');

        return new SignupResource($user);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember_me', false);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials are invalid',
                'errors' => ['email' => ['The provided credentials are invalid']],
            ], 422);
        }

        $user = Auth::user();
        $expiration = $remember ? config('sanctum.expiration_long') : config('sanctum.expiration');

        $tokenResult = $user->createToken('auth-token');

        $tokenModel = $tokenResult->accessToken;
        $tokenModel->expires_at = now()->addMinutes($expiration);
        $tokenModel->save();

        $user->access_token = $tokenResult->plainTextToken;
        $user->expires_in = $tokenModel->expires_at->timestamp;

        return new LoginResource($user);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user?->currentAccessToken()?->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
