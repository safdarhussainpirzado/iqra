<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SubjectController;

// Public Auth routes
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Academic Hierarchy CRUD routes
    Route::apiResource('boards', BoardController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('chapters', ChapterController::class);
});

