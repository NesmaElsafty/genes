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
use App\Http\Controllers\AnimalViewController;
use App\Http\Controllers\AnimalMatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\SettingController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/request-reset', [AuthController::class, 'requestReset']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
   
    // user routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::post('/usersBulk-delete', [UserController::class, 'bulkDelete']);
    Route::post('/usersBulk-toggle', [UserController::class, 'bulkToggle']);
    Route::post('/usersToggle', [UserController::class, 'toggle']);
    Route::post('/usersExportSheet', [UserController::class, 'exportSheet']);
    // block list
    Route::get('/blockList', [UserController::class, 'blockList']);
    Route::post('/userBlock', [UserController::class, 'block']);
    Route::post('/userBulkBlock', [UserController::class, 'bulkBlock']);
    Route::post('/userUnblock', [UserController::class, 'unblock']);
    Route::post('/userBulkUnblock', [UserController::class, 'bulkUnblock']);
    // role routes
    Route::apiResource('roles', RoleController::class);
    
    // farm routes
    Route::apiResource('farms', FarmController::class);
    // only admin can access this route
    Route::get('/selectableFarms', [FarmController::class, 'selectableFarms']);
    Route::apiResource('animal-types', AnimalTypeController::class);
    Route::apiResource('animal-breeds', AnimalBreedController::class);
    Route::apiResource('eventTypes', EventTypeController::class);
    Route::post('/exportFarms',  [FarmController::class, 'exportFarms']);
    
    // animals 
    Route::apiResource('animals', AnimalController::class);
    Route::get('/getAnimalsByGender', [AnimalController::class, 'getAnimalsByGender']);
    
    // animal views
    Route::apiResource('animalViews', AnimalViewController::class);
    Route::get('getByAnimal', [AnimalViewController::class, 'getByAnimal']);
    // Route::get('/animalViewsSe', [AnimalViewController::class, 'search']);
    
    // animal matings
    Route::apiResource('animal-matings', AnimalMatingController::class);
    Route::get('getMatingsByAnimal', [AnimalMatingController::class, 'getByAnimal']);
    Route::get('getMatingsByType/{matingType}', [AnimalMatingController::class, 'getByType']);
    
    // events
    Route::apiResource('events', EventController::class);
    Route::get('getByAnimal', [EventController::class, 'getByAnimal']);

    // home routes
    Route::get('/homeAnimals', [HomeController::class, 'animals']);
    Route::get('/clientStats', [HomeController::class, 'clientStats']);
    Route::get('/animalEventTypeStats', [HomeController::class, 'animalEventTypeStats']);
    Route::get('/animalBreedStats', [HomeController::class, 'animalBreedStats']);
    Route::get('/selectableFarms', [HomeController::class, 'selectableFarms']);
    Route::get('/latestAnimalEvents', [HomeController::class, 'latestAnimalEvents']);
    
    // admin home routes
    Route::get('/adminStats', [AdminHomeController::class, 'stats']);
    Route::get('/farmRegistrationTrends', [AdminHomeController::class, 'farmRegistrationTrends']);
    Route::get('/userRegistrationTrends', [AdminHomeController::class, 'userRegistrationTrends']);
    Route::get('/globalAnimalEventTypeStats', [AdminHomeController::class, 'globalAnimalEventTypeStats']);
    Route::get('/globalAnimalBreedStats', [AdminHomeController::class, 'globalAnimalBreedStats']);
    Route::get('/mostActiveFarms', [AdminHomeController::class, 'mostActiveFarms']);
    // FAQ routes
    Route::apiResource('faqs', FaqController::class);
    Route::post('/faqsBulk-delete', [FaqController::class, 'bulkDelete']);
    Route::post('/faqsBulk-toggle', [FaqController::class, 'bulkToggle']);
    Route::post('/faqsToggle', [FaqController::class, 'toggle']);
    Route::post('/faqsExportSheet', [FaqController::class, 'exportSheet']);

    // Terms routes
    Route::apiResource('terms', TermController::class);
    Route::post('/termsBulk-delete', [TermController::class, 'bulkDelete']);
    Route::post('/termsBulk-toggle', [TermController::class, 'bulkToggle']);
    Route::post('/termsToggle', [TermController::class, 'toggle']);
    Route::post('/termsExportSheet', [TermController::class, 'exportSheet']);
    
    // Notification routes
    Route::apiResource('notifications', NotificationController::class);
    Route::post('/notificationsBulk-delete', [NotificationController::class, 'bulkDelete']);
    Route::post('/notificationsExportSheet', [NotificationController::class, 'exportSheet']);
    Route::post('/notifications/{id}/send', [NotificationController::class, 'sendNotification']);
    
    // Alert routes
    Route::get('/alerts', [AlertController::class, 'index']);
    Route::get('/alerts/{id}', [AlertController::class, 'show']);
    Route::post('/alerts/{id}/mark-read', [AlertController::class, 'markAsRead']);
    Route::get('/alerts/unread-count', [AlertController::class, 'unreadCount']);
    
    // Setting routes
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/settings/{id}', [SettingController::class, 'show']);
    Route::post('/settingsToggle', [SettingController::class, 'toggle']);
}); 