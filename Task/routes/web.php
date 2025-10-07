<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login.form');
});
 
Route::get('/report', [ReportController::class, 'index'])->name('report.index');
Route::post('/report', [ReportController::class, 'filter'])->name('report.filter');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
