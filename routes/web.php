<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBusController;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/dashboard', function () {
    $bookingsQuery = \App\Models\Booking::where('user_id', \Illuminate\Support\Facades\Auth::id());

    $totalBookings = clone $bookingsQuery;
    $totalBookingsCount = $totalBookings->count();

    $activeTrips = clone $bookingsQuery;
    $activeTripsCount = $activeTrips->whereIn('status', ['accepted', 'pending', 'countered'])->count();

    $recentBookings = $bookingsQuery->latest()->take(3)->get();

    return view('dashboard', compact('totalBookingsCount', 'activeTripsCount', 'recentBookings'));
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\MpesaController;

Route::post('/payments/mpesa/callback', [MpesaController::class, 'callback'])->name('payments.mpesa.callback');

Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

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
        Route::post('bookings/{booking}/payment/confirm', [AdminBookingController::class, 'confirmPayment'])->name('bookings.payment.confirm');
        Route::post('bookings/{booking}/payment/reject', [AdminBookingController::class, 'rejectPayment'])->name('bookings.payment.reject');
        Route::get('bookings/history', [AdminBookingController::class, 'history'])->name('bookings.history');
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show']);

        // Schedule
        Route::get('schedule', [AdminScheduleController::class, 'index'])->name('schedule.index');

        // Receipts Management
        Route::get('receipts/system', [\App\Http\Controllers\AdminReceiptController::class, 'systemIndex'])->name('receipts.system');
        Route::post('receipts/{receipt}/send', [\App\Http\Controllers\AdminReceiptController::class, 'send'])->name('receipts.send');
        Route::resource('receipts', \App\Http\Controllers\AdminReceiptController::class);

        // Insurance & Inspection Management
        Route::resource('insurance', \App\Http\Controllers\AdminInsuranceController::class);
        Route::resource('inspection', \App\Http\Controllers\AdminInspectionController::class);

        // Billing & Bus Services
        Route::get('billing', [\App\Http\Controllers\BusServiceController::class, 'adminIndex'])->name('billing.index');
        Route::post('billing', [\App\Http\Controllers\BusServiceController::class, 'adminStore'])->name('billing.store');
        Route::patch('bus-service/{busService}/status', [\App\Http\Controllers\BusServiceController::class, 'updateStatus'])->name('bus-service.update-status');
    });

    // Booking Routes (User)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/schedule', [BookingController::class, 'schedule'])->name('bookings.schedule');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/accept', [BookingController::class, 'acceptCounter'])->name('bookings.accept-counter');
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'submitPayment'])->name('bookings.pay');
    Route::get('/receipts', [BookingController::class, 'indexReceipts'])->name('receipts.index');
    Route::get('/receipts/{booking}', [BookingController::class, 'showReceipt'])->name('receipts.view');

    // Driver Routes
    Route::middleware('role:driver')->group(function () {
        Route::get('/driver/dashboard', [DriverController::class, 'index'])->name('driver.dashboard');
        Route::get('/driver/schedule', [DriverController::class, 'schedule'])->name('driver.schedule');
        Route::get('/driver/history', [DriverController::class, 'history'])->name('driver.history');
        Route::get('/driver/license', [DriverController::class, 'license'])->name('driver.license');
        Route::get('/driver/insurance', [DriverController::class, 'insurance'])->name('driver.insurance');
        Route::get('/driver/inspection', [DriverController::class, 'inspection'])->name('driver.inspection');

        // Bus Service
        Route::get('/driver/bus-service', [\App\Http\Controllers\BusServiceController::class, 'driverIndex'])->name('driver.bus-service.index');
        Route::post('/driver/bus-service', [\App\Http\Controllers\BusServiceController::class, 'driverStore'])->name('driver.bus-service.store');
    });
});

require __DIR__ . '/auth.php';

Route::get('/test-email', function () {
    $booking = \App\Models\Booking::latest()->first();
    if (!$booking)
        return "No bookings found.";

    try {
        \Illuminate\Support\Facades\Mail::to('iankamnganga@gmail.com')->send(new \App\Mail\BookingConfirmed($booking));
        return "Success! Check your inbox.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-mpesa-auth', function () {
    $consumerKey = env('MPESA_CONSUMER_KEY');
    $consumerSecret = env('MPESA_CONSUMER_SECRET');

    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

    $ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    return response()->json(json_decode($response));
});

Route::get('/test-mpesa-diagnose', function () {
    $service = app(App\Services\DarajaStkService::class);
    return response()->json($service->diagnoseAuth());
});

Route::get('/test-email', function () {
    $booking = \App\Models\Booking::latest()->first();
    if (!$booking)
        return "No bookings found.";

    try {
        \Illuminate\Support\Facades\Mail::to('iankamnganga@gmail.com')->send(new \App\Mail\BookingConfirmed($booking));
        return "Success! Check your inbox.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});