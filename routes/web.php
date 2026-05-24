<?php

use App\Http\Controllers\Admin\ParentAssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BmiRecordController;
use App\Http\Controllers\ClinicVisitWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalRecordController;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/analytics', AnalyticsController::class)->name('analytics');
    Route::resource('profiles', ProfileController::class);

    Route::middleware('role:'.User::ROLE_ADMIN)->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/parent-assignments', [ParentAssignmentController::class, 'index'])->name('parent-assignments.index');
        Route::post('/parent-assignments', [ParentAssignmentController::class, 'store'])->name('parent-assignments.store');
        Route::delete('/parent-assignments/{user}/{student}', [ParentAssignmentController::class, 'destroy'])->name('parent-assignments.destroy');
    });

    Route::middleware('role:'.User::ROLE_CLINIC_STAFF)->group(function (): void {
        Route::get('/health-records', [HealthRecordController::class, 'index'])->name('health-records.index');
        Route::get('/profiles/{student}/clinic-visits/create', [ClinicVisitWebController::class, 'create'])->name('clinic-visits.create');
        Route::post('/profiles/{student}/clinic-visits', [ClinicVisitWebController::class, 'store'])->name('clinic-visits.store');
        Route::get('/profiles/{student}/bmi/create', [BmiRecordController::class, 'create'])->name('bmi-records.create');
        Route::post('/profiles/{student}/bmi', [BmiRecordController::class, 'store'])->name('bmi-records.store');
        Route::get('/profiles/{student}/dental', [DentalRecordController::class, 'edit'])->name('dental-records.edit');
        Route::post('/profiles/{student}/dental', [DentalRecordController::class, 'store'])->name('dental-records.store');
    });
});
