<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ViolationLogController;
use App\Http\Controllers\Peserta\ExamController;
use App\Http\Controllers\Admin\AdminProctoringController;

// Autentikasi Pengguna
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome'); // Pastikan nama file blade Anda adalah welcome.blade.php
    })->name('welcome');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
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
        Route::post('/api/violation-log', [App\Http\Controllers\Admin\ViolationLogController::class, 'store'])
            ->name('admin.monitoring.store');
        Route::delete('/monitoring/clear', [ViolationLogController::class, 'clearAll'])->name('monitoring.clear');
        Route::get('/admin/sessions', [AdminProctoringController::class, 'indexSessions'])->name('admin.sessions');
        Route::post('/admin/sessions', [AdminProctoringController::class, 'storeSession'])->name('admin.sessions.store');
        Route::patch('/admin/sessions/{id}/activate', [AdminProctoringController::class, 'activateSession'])->name('admin.sessions.activate');
        Route::get('/violation-history', [AdminProctoringController::class, 'history'])->name('admin.history');
    });

    // Kelompok Route Peserta Ujian TOEFL
    Route::middleware(['role:peserta'])->group(function () {
        Route::get('/exam/calibration', [ExamController::class, 'calibration'])->name('exam.calibration');
        Route::get('/exam', [ExamController::class, 'index'])->name('exam.index');
        Route::post('/exam/violation-log', [ViolationLogController::class, 'store'])->name('exam.violation.store');
        Route::get('/peserta/exam-summary', [ExamController::class, 'terminatedSummary'])->name('peserta.exam.summary');
        Route::get('/peserta/exam-retake', [ExamController::class, 'retakeExam'])->name('peserta.exam.retake');
    });
});
