<?php

use App\Services\CallingAllPapers;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Services\AirtableService;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/book', function () {
    
    $bookings = AirtableService::getNextBooking();

    return view('bokn', ['booking' => $bookings]);
});

// Authentication views
Route::get('/auth/logg-inn', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/logg-inn/engangskode', function () {
    return view('auth.verify-otp');
})->name('verify-otp');

// Authentication API routes
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/min-side', function () {
        $booking = ['time' => '26-august-2025', 'location' => 'Sandaker'];
        return view('dashboard', ['booking' => $booking]);
    })->name('dashboard');
});

 Route::get('/bokn/{time}', function ($time) {
    
    $bookings = [
        ['time' => '26-august-2025', 'location' => 'Sandaker'],
        ['time' => '27-august-2025', 'location' => 'Sandaker'],
        ['time' => '28-august-2025', 'location' => 'Sandaker'],
    ];

    $booking = collect($bookings)->first(fn ($booking) => $booking['time'] = $time);
    
    return view('booking', ['booking' => $booking]);
 });


// Booking views
Route::get('/booking/{id}', function ($id) {
    
    $bookings = [
        ['id' => 1, 'time' => '26. august 2025', 'location' => 'Sandaker'],
        ['id' => 2, 'time' => '27. august 2025', 'location' => 'Sandaker'],
        ['id' => 3, 'time' => '28. august 2025', 'location' => 'Sandaker'],
    ];

    $booking = collect($bookings)->first(fn ($booking) => $booking['id'] = $id);
    
    return view('booking', ['booking' => $booking]);
});