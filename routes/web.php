<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorcycleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/motorcycles', [MotorcycleController::class, 'index'])
    ->name('motorcycles.index');

Route::get('/motorcycles/{motorcycle}', [MotorcycleController::class, 'show'])
    ->name('motorcycles.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';