<?php

use Illuminate\Support\Facades\Route;

// Minimal API route to ensure the file exists and can be required by Laravel
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});


