<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\otpController;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\DoctorController;

use App\Http\Controllers\Api\StripeController;

Route::middleware('auth:sanctum')->group(function () {
    // Booking routes
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::put('bookings/{booking}/update', [BookingController::class, 'update']);
    Route::delete('bookings/{booking}/cancel', [BookingController::class, 'destroy']);
    Route::middleware('auth:sanctum')->group(function () {
    //booking routes
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::put('bookings/{booking}/update', [BookingController::class, 'update']);
    Route::delete('bookings/{booking}/cancel', [BookingController::class, 'destroy']);
    
    //payment routes
    Route::post('/bookings/checkout/{bookingId}', [StripeController::class, 'checkout']);

    //doctor & patient bookings
    Route::get('doctor/bookings', [BookingController::class, 'doctorBookings']);
    Route::get('patient/bookings', [BookingController::class, 'patientBookings']);

    //chat system
    Route::apiResource('chat', ChatController::class)->only(['index','store','show']);
    Route::apiResource('chat_message', MessageController::class)->only(['index','store']);

    //reviews
    Route::post('reviews', [ReviewController::class, 'store']);

    //session feedback
    Route::post('session-feedback', [SessionFeedbackController::class, 'store']);
});


    Route::get('doctor/bookings', [BookingController::class, 'doctorBookings']);
    Route::get('patient/bookings', [BookingController::class, 'patientBookings']);
    //payment routes
    Route::post('/bookings/checkout/{bookingId}', [StripeController::class, 'checkout']);

    // Chat routes
    Route::apiResource('chat', ChatController::class)->only(['index','store','show']);
    Route::apiResource('chat_message', MessageController::class)->only(['index','store']);

    // Home & Doctors routes
    Route::get('/home/doctors', [HomeController::class, 'nearby']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::get('/doctors/{doctor}/reviews', [DoctorController::class, 'reviews']);
    Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);
});

Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('auth')->controller(PasswordController::class)->group(function () {
    Route::post('forgot-password',  'sendResetCode');
    Route::post('reset-password', 'resetPassword');
});

Route::prefix('otp')->controller(otpController::class)->group(function () {
    Route::post('verify', 'verifyOtp');
});

Route::post('google/login', [GoogleController::class, 'LogInWithGoogle']);
Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{user}', 'show');
    Route::patch('/update', 'update');
    Route::delete('/delete', 'destroy');
});





