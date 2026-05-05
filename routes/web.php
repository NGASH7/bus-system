<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBusController;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    $bookingsQuery = \App\Models\Booking::where('user_id', \Illuminate\Support\Facades\Auth::id());

    $totalBookings = clone $bookingsQuery;
    $totalBookingsCount = $totalBookings->count();

    $activeTrips = clone $bookingsQuery;
    $activeTripsCount = $activeTrips->whereIn('status', ['accepted', 'pending', 'countered'])->count();

    $recentBookings = $bookingsQuery->latest()->take(3)->get();

    return view('dashboard', compact('totalBookingsCount', 'activeTripsCount', 'recentBookings'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Change Route
    Route::get('/password/change', function () {
        return view('auth.change-password');
    })->name('password.change');

    Route::post('/password/change', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Password updated successfully. You now have full access.');
    })->name('password.update_required');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');

        // Bus Management
        Route::get('buses/{bus}', [AdminBusController::class, 'show'])
            ->whereNumber('bus')
            ->name('buses.show');
        Route::resource('buses', AdminBusController::class)->except(['show']);

        // Driver Management
        Route::resource('drivers', AdminDriverController::class);

        // Analytics
        Route::get('analytics', [\App\Http\Controllers\AdminAnalyticsController::class, 'index'])->name('analytics.index');

        // License Management
        Route::resource('licenses', \App\Http\Controllers\AdminLicenseController::class);

        // Bookings Management
        Route::post('bookings/{booking}/accept', [AdminBookingController::class, 'accept'])->name('bookings.accept');
        Route::post('bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
        Route::post('bookings/{booking}/counter', [AdminBookingController::class, 'counter'])->name('bookings.counter');
        Route::get('bookings/history', [AdminBookingController::class, 'history'])->name('bookings.history');
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show']);

        // Schedule
        Route::get('schedule', [AdminScheduleController::class, 'index'])->name('schedule.index');

        // Receipts Management
        Route::get('receipts/system', [\App\Http\Controllers\AdminReceiptController::class, 'systemIndex'])->name('receipts.system');
        Route::post('receipts/{receipt}/send', [\App\Http\Controllers\AdminReceiptController::class, 'send'])->name('receipts.send');
        Route::resource('receipts', \App\Http\Controllers\AdminReceiptController::class);

        // Insurance Management
        Route::resource('insurance', \App\Http\Controllers\AdminInsuranceController::class);

        // Billing & Bus Services
        Route::get('billing', [\App\Http\Controllers\BusServiceController::class, 'adminIndex'])->name('billing.index');
        Route::post('billing', [\App\Http\Controllers\BusServiceController::class, 'adminStore'])->name('billing.store');
        Route::patch('bus-service/{busService}/status', [\App\Http\Controllers\BusServiceController::class, 'updateStatus'])->name('bus-service.update-status');
    });

    // Booking Routes (User)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/accept', [BookingController::class, 'acceptCounter'])->name('bookings.accept-counter');
    Route::get('/receipts', [BookingController::class, 'indexReceipts'])->name('receipts.index');
    Route::get('/receipts/{booking}', [BookingController::class, 'showReceipt'])->name('receipts.view');

    // Driver Routes
    Route::middleware('role:driver')->group(function () {
        Route::get('/driver/dashboard', [DriverController::class, 'index'])->name('driver.dashboard');
        Route::get('/driver/schedule', [DriverController::class, 'schedule'])->name('driver.schedule');
        Route::get('/driver/history', [DriverController::class, 'history'])->name('driver.history');
        Route::get('/driver/license', [DriverController::class, 'license'])->name('driver.license');
        Route::get('/driver/insurance', [DriverController::class, 'insurance'])->name('driver.insurance');
        
        // Bus Service
        Route::get('/driver/bus-service', [\App\Http\Controllers\BusServiceController::class, 'driverIndex'])->name('driver.bus-service.index');
        Route::post('/driver/bus-service', [\App\Http\Controllers\BusServiceController::class, 'driverStore'])->name('driver.bus-service.store');
    });
});

require __DIR__ . '/auth.php';