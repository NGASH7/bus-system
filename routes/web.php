<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBusController;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Bus Management
        Route::get('buses/{bus}', [AdminBusController::class, 'show'])
            ->whereNumber('bus')
            ->name('buses.show');
        Route::resource('buses', AdminBusController::class)->except(['show']);

        // Driver Management
        Route::resource('drivers', AdminDriverController::class)->except(['show']);
    });

    // Driver Routes
    Route::middleware('role:driver')->group(function () {
        Route::get('/driver/dashboard', [DriverController::class, 'index'])->name('driver.dashboard');
    });
});

require __DIR__ . '/auth.php';

