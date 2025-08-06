<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Booking views
Route::get('/booking', function () {
    return view('booking', [
        'bookings' => [
            ['id' => 1, 'time' => '26. august 2025', 'location' => 'Sandaker'],
            ['id' => 2, 'time' => '27. august 2025', 'location' => 'Sandaker'],
            ['id' => 3, 'time' => '28. august 2025', 'location' => 'Sandaker'],
        ],
    ]);
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
        return view('dashboard');
    })->name('dashboard');
});
