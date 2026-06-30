<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\GroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/documents/history', [DocumentController::class, 'history']);
    Route::get('/documents/{document}/fields', [DocumentController::class, 'fields']);
    Route::post('/documents/{document}/generate', [DocumentController::class, 'generate']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('api.documents.download');
    Route::get('/generated/{generated}/download', [DocumentController::class, 'downloadGenerated'])->name('api.generated.download');

    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{group}/fields', [GroupController::class, 'fields']);
    Route::post('/groups/{group}/generate', [GroupController::class, 'generate']);
    Route::get('/groups/{group}/download', [GroupController::class, 'download'])->name('api.groups.download');
});
