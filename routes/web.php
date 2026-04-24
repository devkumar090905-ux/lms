<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookingController;

Route::get('/', [AdminController::class, 'index']);

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
