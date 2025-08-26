<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api'])->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
});

Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/password/forgot', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
});
