<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/request-reset', [AuthController::class, 'requestReset']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
    Route::apiResource('farms', \App\Http\Controllers\FarmController::class);
}); 