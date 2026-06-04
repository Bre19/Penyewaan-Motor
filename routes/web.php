<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorcycleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/motorcycles', [MotorcycleController::class, 'index'])
    ->name('motorcycles.index');

Route::get('/motorcycles/{motorcycle}', [MotorcycleController::class, 'show'])
    ->name('motorcycles.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/motorcycles/{motorcycle}/booking', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/motorcycles/{motorcycle}/booking', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/bookings/{booking}', [BookingController::class, 'show'])
        ->name('bookings.show');

    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)
            ->name('dashboard');

        Route::get('/bookings', [AdminBookingController::class, 'index'])
            ->name('bookings.index');

        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])
            ->name('bookings.show');

        Route::patch('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])
            ->name('bookings.approve');

        Route::patch('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])
            ->name('bookings.reject');
    });

require __DIR__ . '/auth.php';