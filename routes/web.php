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

Route::middleware('auth')->group(function () {
    Route::get('/book', function () {
        
        $bookings = AirtableService::getNextBooking();

        return view('bokn', ['booking' => $bookings]);
    });
});