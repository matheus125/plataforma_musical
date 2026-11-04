<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\SongController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/songs', [SongController::class, 'index']);
Route::get('/songs/{song}', [SongController::class, 'show']);

Route::get('/performances', [PerformanceController::class, 'index']);
Route::get('/performances/{performance}', [PerformanceController::class, 'show']);
Route::get('/users/{id}/performances', [PerformanceController::class, 'userIndex']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/performances', [PerformanceController::class, 'store']);
    Route::delete('/performances/{performance}', [PerformanceController::class, 'destroy']);

    Route::post('/admin/songs', [SongController::class, 'store']);
    Route::put('/admin/songs/{song}', [SongController::class, 'update']);
    Route::delete('/admin/songs/{song}', [SongController::class, 'destroy']);
});
