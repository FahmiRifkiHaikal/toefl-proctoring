<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ViolationLogController;
use App\Http\Controllers\Peserta\ExamController;

// Autentikasi Pengguna
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome'); // Pastikan nama file blade Anda adalah welcome.blade.php
    })->name('welcome');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Proteksi Wajib Login
Route::middleware(['auth'])->group(function () {

    // Kelompok Route Admin ITN Malang
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [ViolationLogController::class, 'index'])->name('monitoring');
        Route::delete('/monitoring/clear', [ViolationLogController::class, 'clearAll'])->name('monitoring.clear');
    });

    // Kelompok Route Peserta Ujian TOEFL
    Route::middleware(['role:peserta'])->group(function () {
        Route::get('/exam/calibration', [ExamController::class, 'calibration'])->name('exam.calibration');
        Route::get('/exam', [ExamController::class, 'index'])->name('exam.index');
        Route::post('/exam/violation-log', [ViolationLogController::class, 'store'])->name('exam.violation.store');
    });
});
