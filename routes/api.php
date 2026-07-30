<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\IngestionController;
use App\Http\Controllers\PaperGeneratorController;
use App\Http\Controllers\JobsController;

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

    // Ingestion & Web scraping routes
    Route::post('/ingest', [IngestionController::class, 'ingest']);
    Route::post('/scrape', [IngestionController::class, 'scrape']);
    Route::get('/notes', [IngestionController::class, 'getNotes']);
    Route::get('/materials', [IngestionController::class, 'getMaterials']);
    Route::put('/notes/{note}', [IngestionController::class, 'updateNote']);
    Route::put('/materials/{material}', [IngestionController::class, 'updateMaterial']);

    // Question Bank & Paper Generator routes
    Route::get('/questions', [PaperGeneratorController::class, 'getQuestions']);
    Route::post('/questions', [PaperGeneratorController::class, 'storeQuestion']);
    Route::delete('/questions/{question}', [PaperGeneratorController::class, 'destroyQuestion']);
    Route::post('/generate-paper', [PaperGeneratorController::class, 'generatePaper']);
    Route::get('/papers', [PaperGeneratorController::class, 'getPapers']);

    // Logs & Reports
    Route::get('/logs', function () {
        return \App\Models\ActivityLog::with('user')->orderBy('id', 'desc')->take(200)->get();
    });

    // Queue & Jobs Monitor
    Route::get('/jobs', [JobsController::class, 'index']);
    Route::delete('/jobs/failed/{id}', [JobsController::class, 'destroyFailed']);
    Route::post('/jobs/failed/{id}/retry', [JobsController::class, 'retryFailed']);

    // Delete routes for library items
    Route::delete('/notes/{note}', [IngestionController::class, 'destroyNote']);
    Route::delete('/materials/{material}', [IngestionController::class, 'destroyMaterial']);

    // Question Bank full CRUD
    Route::put('/questions/{question}', [PaperGeneratorController::class, 'updateQuestion']);
});

