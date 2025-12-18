<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BpmSingleController;
use App\Http\Controllers\Api\BpmController;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Halaman login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Dashboard 
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/dashboard/realtime', [DashboardController::class,'realtime'])->name('dashboard.realtime');
    Route::delete('/dashboard/truncate', [DashboardController::class,'truncate'])->name('dashboard.truncate');
    Route::get('/dashboard/export', [DashboardController::class,'export'])->name('dashboard.export');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/cek-bpm', [BpmSingleController::class, 'index'])->name('cek.bpm');
Route::get('/cek-bpm/data', [BpmSingleController::class, 'data'])->name('cek.bpm.data');

Route::get('/dashboard/buffplot-realtime', 
    [DashboardController::class, 'buffplotRealtime']
)->name('dashboard.buffplot.realtime');



// // Endpoint untuk menyimpan data BPM
// Route::post('/bpm', [BpmController::class, 'store'])->name('api.bpm.store');

// // Endpoint untuk menampilkan list BPM
// Route::get('/bpm', [BpmController::class, 'index'])->name('api.bpm.index');