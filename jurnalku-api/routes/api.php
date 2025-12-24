<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 

// Login route
Route::post('/login', [AuthController::class, 'login']);

// Get all users (public route)
Route::get('/users', [AuthController::class, 'getAllUsers']);

// Protected routes (require token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/upload-photo', [AuthController::class, 'uploadPhoto']);
    
    // Get current user
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
});
