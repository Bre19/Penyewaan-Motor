<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MotorcycleController as AdminMotorcycleController;
use App\Http\Controllers\Admin\MotorcycleStockController as AdminMotorcycleStockController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\GpsTrackingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\PaymentController;
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

    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'create'])
        ->name('payments.create');

    Route::post('/bookings/{booking}/payment', [PaymentController::class, 'store'])
        ->name('payments.store');
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

        Route::get('/gps-tracker', function () {
            return view('admin.gps.index');
        })->name('gps.index');

        Route::patch('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])
            ->name('bookings.approve');

        Route::patch('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])
            ->name('bookings.reject');
        
        Route::patch('/bookings/{booking}/ready-to-deliver', [AdminBookingController::class, 'markReadyToDeliver'])
            ->name('bookings.markReadyToDeliver');

        Route::get('/bookings/{booking}/handover', [AdminBookingController::class, 'handover'])
            ->name('bookings.handover');

        Route::patch('/bookings/{booking}/handover', [AdminBookingController::class, 'storeHandover'])
            ->name('bookings.storeHandover');

        Route::get('/bookings/{booking}/complete', [AdminBookingController::class, 'complete'])
            ->name('bookings.complete');

        Route::patch('/bookings/{booking}/complete', [AdminBookingController::class, 'storeCompletion'])
            ->name('bookings.storeCompletion');

        Route::resource('motorcycles', AdminMotorcycleController::class)
            ->except(['show']);
        
        Route::get('/gps-tracking', [GpsTrackingController::class, 'index'])
            ->name('gps.index');

        Route::resource('motorcycle-stocks', AdminMotorcycleStockController::class)
            ->except(['show']);

        Route::get('/payments', [AdminPaymentController::class, 'index'])
            ->name('payments.index');

        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])
            ->name('payments.show');

        Route::patch('/payments/{payment}/confirm', [AdminPaymentController::class, 'confirm'])
            ->name('payments.confirm');

        Route::patch('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])
            ->name('payments.reject');

        Route::view(
            '/incident-reports',
            'admin.incident-reports.index'
        )->name('incident-reports.index');

        Route::view(
            '/incident-reports/{id}',
            'admin.incident-reports.show'
        )->name('incident-reports.show');
    });

require __DIR__ . '/auth.php';
