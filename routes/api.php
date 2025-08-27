<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


// Route::middleware(['auth:api', 'permission:edit posts'])->group(function () {
//     Route::put('/posts/{id}', [AuthController::class, 'update']);
// });

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::apiResource('users', UserController::class);
});

