<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-doc', [App\Http\Controllers\DocController::class, 'test'])->name('test');
Route::get('/test', [App\Http\Controllers\DocController::class, 'index'])->name('index');