<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('documents.index')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');

});

Route::middleware('auth')->group(function () {

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

    Route::get('/documents/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::get('/documents/history', [DocumentController::class, 'history'])->name('documents.history');
    Route::get('/generated/{generated}/download', [DocumentController::class, 'downloadGenerated'])->name('generated.download');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    Route::post('/documents/{document}/generate', [DocumentController::class, 'generate'])->name('documents.generate');

    Route::get('/groups/download', [GroupController::class, 'download'])->name('groups.download');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/generate', [GroupController::class, 'generate'])->name('groups.generate');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});