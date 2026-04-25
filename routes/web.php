<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingController;

use App\Http\Controllers\SuperAdminController;

// Landing & Setup Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/setup', [LandingController::class, 'setup'])->name('setup');
Route::post('/login', [LandingController::class, 'login'])->name('login');
Route::post('/logout', [LandingController::class, 'logout'])->name('logout');

// Super Admin Routes
Route::prefix('super-admin')->group(function () {
    Route::get('/login', [SuperAdminController::class, 'loginView'])->name('superadmin.login.view');
    Route::post('/login', [SuperAdminController::class, 'login'])->name('superadmin.login');
    Route::post('/logout', [SuperAdminController::class, 'logout'])->name('superadmin.logout');
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/library/{id}/edit', [SuperAdminController::class, 'editLibrary'])->name('superadmin.library.edit');
    Route::put('/library/{id}/update', [SuperAdminController::class, 'updateLibrary'])->name('superadmin.library.update');
    Route::delete('/library/{id}/delete', [SuperAdminController::class, 'deleteLibrary'])->name('superadmin.library.delete');
});

// Dashboard (Protected later if needed)
Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Seat Management Routes
Route::get('/seats', [SeatController::class, 'index'])->name('seats.index');
Route::post('/seats', [SeatController::class, 'store'])->name('seats.store');
Route::put('/seats/{seat}', [SeatController::class, 'update'])->name('seats.update');
Route::delete('/seats/{seat}', [SeatController::class, 'destroy'])->name('seats.destroy');

// Student Management Routes
Route::resource('students', StudentController::class)->except(['create', 'show', 'edit']);

// Booking Management Routes
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
