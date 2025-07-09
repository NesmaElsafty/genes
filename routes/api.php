<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FarmController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/request-reset', [AuthController::class, 'requestReset']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
   
    // user routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::post('/usersBulk-delete', [UserController::class, 'bulkDelete']);
    Route::post('/usersBulk-toggle', [UserController::class, 'bulkToggle']);
    Route::post('/usersToggle', [UserController::class, 'toggle']);
    Route::post('/usersExportSheet', [UserController::class, 'exportSheet']);
    // role routes
    Route::apiResource('roles', RoleController::class);
    // farm routes
    Route::apiResource('farms', FarmController::class);
}); 