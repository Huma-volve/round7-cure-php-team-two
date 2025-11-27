<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SessionFeedbackController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Dashboard\ChatController;


// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

// Password reset routes
Route::prefix('auth')->controller(PasswordController::class)->group(function () {
    Route::post('forgot-password', 'sendResetCode');
    Route::post('reset-password', 'resetPassword');
});

// 🔢 OTP routes
Route::prefix('otp')->controller(OtpController::class)->group(function () {
    Route::post('verify', 'verifyOtp');
});

// 🔄 Google login
Route::post('google/login', [GoogleController::class, 'LogInWithGoogle']);

// 👤 User routes
Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{user}', 'show');
    Route::patch('/update', 'update');
    Route::delete('/delete', 'destroy');
});

// 🧾 Authenticated routes
Route::middleware(['auth:sanctum','throttle:api'])->group(function () {
    // Booking routes
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::put('bookings/{id}/update', [BookingController::class, 'reschedule']);
    Route::delete('bookings/{id}/cancel', [BookingController::class, 'cancel']);
    //payment routes
    Route::post('/bookings/checkout/{bookingId}', [StripeController::class, 'checkout']);

    // Doctor & patient bookings
    Route::get('doctor/bookings', [BookingController::class, 'doctorBookings']);
    Route::get('patient/bookings', [BookingController::class, 'patientBookings']);

    Route::get('conversations', [ConversationController::class, 'index']);
    Route::get('conversations/{conversation}', [ConversationController::class, 'show']);
    // Route::post('conversations/{conversation}/participants', [ConversationController::class, 'addParticipant']);
    // Route::delete('conversations/{conversation}/participants', [ConversationController::class, 'removeParticipant']);
    Route::put('conversations/{conversation}/read', [ConversationController::class, 'markAsRead']);
    Route::get('conversations/{id}/messages', [MessageController::class, 'index']);
    Route::post('messages', [MessageController::class, 'store'])
        ->name('api.messages.store');
    Route::delete('messages/{id}', [MessageController::class, 'destroy']);

    // Reviews
    Route::post('reviews', [ReviewController::class, 'store']);

    // Session feedback
    Route::post('session-feedback', [SessionFeedbackController::class, 'store']);

    // Home & Doctors
    Route::get('/home/doctors', [HomeController::class, 'nearby']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::get('/doctors/{doctor}/reviews', [DoctorController::class, 'reviews']);
    Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);

    // Search
    Route::post('/search', [SearchController::class, 'search']);
    Route::get('/search/history', [SearchController::class, 'history']);
    Route::delete('/search/history', [SearchController::class, 'clearHistory']);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'add']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'remove']);
});

// Stripe webhook
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
