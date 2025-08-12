<?php

// Can I use CallingAllPapers in here?
use App\Services\CallingAllPapers;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

class GetNextBooking
{
    public static function all(): array
    {
        return ['time' => '26. august 2025', 'location' => 'Sandaker'];
    }
}

Route::get('/bokn', function () {
    return view('bokn', ['booking' => GetNextBooking::all()]);
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