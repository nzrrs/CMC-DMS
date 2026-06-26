<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'service' => 'CMC-DMS API',
        'status' => 'OK',
    ]);
});