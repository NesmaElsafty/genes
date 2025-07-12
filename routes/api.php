<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\AnimalTypeController;
use App\Http\Controllers\AnimalBreedController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TermController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/request-reset', [AuthController::class, 'requestReset']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
   
    // user routes
    Route::get('/user', [AuthController::class, 'user'])->middleware('role:admin');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::post('/usersBulk-delete', [UserController::class, 'bulkDelete'])->middleware('role:admin');
    Route::post('/usersBulk-toggle', [UserController::class, 'bulkToggle'])->middleware('role:admin');
    Route::post('/usersToggle', [UserController::class, 'toggle'])->middleware('role:admin');
    Route::post('/usersExportSheet', [UserController::class, 'exportSheet'])->middleware('role:admin');
    // block list
    Route::get('/blockList', [UserController::class, 'blockList'])->middleware('role:admin');
    Route::post('/userBlock', [UserController::class, 'block'])->middleware('role:admin');
    Route::post('/userBulkBlock', [UserController::class, 'bulkBlock'])->middleware('role:admin');
    Route::post('/userUnblock', [UserController::class, 'unblock'])->middleware('role:admin');
    Route::post('/userBulkUnblock', [UserController::class, 'bulkUnblock'])->middleware('role:admin');
    // role routes
    Route::apiResource('roles', RoleController::class)->middleware('role:admin');
    
    // farm routes
    Route::apiResource('farms', FarmController::class)->middleware('role:admin');
    // only admin can access this route
    Route::get('/selectableFarms', [FarmController::class, 'selectableFarms'])->middleware('role:admin');
    Route::apiResource('animal-types', AnimalTypeController::class);
    Route::apiResource('animal-breeds', AnimalBreedController::class);
    Route::apiResource('eventTypes', EventTypeController::class);
    Route::post('/exportFarms',  [FarmController::class, 'exportFarms'])->middleware('role:admin');
    
    // animals 
    Route::apiResource('animals', AnimalController::class);
    Route::get('/getAnimalsByGender', [AnimalController::class, 'getAnimalsByGender']);
    
    // events
    Route::apiResource('events', EventController::class);

    // home routes
    Route::get('/homeAnimals', [HomeController::class, 'animals']);
    
    // FAQ routes
    Route::apiResource('faqs', FaqController::class);
    Route::post('/faqsBulk-delete', [FaqController::class, 'bulkDelete']);
    Route::post('/faqsBulk-toggle', [FaqController::class, 'bulkToggle']);
    Route::post('/faqsToggle', [FaqController::class, 'toggle']);
    
    // Terms routes
    Route::apiResource('terms', TermController::class);
    Route::post('/termsBulk-delete', [TermController::class, 'bulkDelete']);
    Route::post('/termsBulk-toggle', [TermController::class, 'bulkToggle']);
    Route::post('/termsToggle', [TermController::class, 'toggle']);
    Route::post('/termsExportSheet', [TermController::class, 'exportSheet']);
}); 