<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PolicyAttributeController;
use App\Http\Controllers\PolicyAttributeValuesController;
use App\Http\Controllers\NotificationController;


// Route::middleware(['auth:api', 'permission:edit posts'])->group(function () {
//     Route::put('/posts/{id}', [AuthController::class, 'update']);
// });

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('agent/registration', [AuthController::class, 'agent_registration']);
Route::apiResource('agents', AgentController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('insurance_providers', InsuranceController::class);
Route::apiResource('policies', PolicyController::class);


Route::prefix('notifications')->group(function () {
    Route::post('welcome', [NotificationController::class, 'sendWelcome']);
    Route::post('policy-created', [NotificationController::class, 'sendPolicyCreated']);
    Route::post('payment-confirmation', [NotificationController::class, 'sendPaymentConfirmation']);
    Route::post('bulk', [NotificationController::class, 'sendBulkNotification']);
    Route::get('history/{userId}', [NotificationController::class, 'getNotificationHistory']);
});





Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    // Route::apiResource('users', UserController::class);



    // api endpoints
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('policy_attributes', PolicyAttributeController::class);
    Route::apiResource('policy_attribute_values', PolicyAttributeValuesController::class);

    // Notification endpoints


});

