<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('{organization_slug}')->group(function () {
    // Events
    Route::apiResource('events', \App\Http\Controllers\EventController::class);
    
    // Attendees
    Route::apiResource('events.attendees', \App\Http\Controllers\AttendeeController::class)
        ->only(['index', 'store', 'show']);
});