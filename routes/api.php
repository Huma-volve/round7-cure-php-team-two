<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\otpController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::put('bookings/{booking}/update', [BookingController::class, 'update']);
    Route::delete('bookings/{booking}/cancel', [BookingController::class, 'destroy']);

    Route::get('doctor/bookings', [BookingController::class, 'doctorBookings']);
    Route::get('patient/bookings', [BookingController::class, 'patientBookings']);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('auth')->controller(PasswordController::class)->group(function () {
    Route::post('forgot-password',  'sendResetCode');


    Route::post('reset-password',  'resetPassword');

});
Route::prefix('otp')->controller(otpController::class)->group(function () {
    Route::post('verify',  'verifyOtp');
});


Route::post('google/login', [GoogleController::class, 'LogInWithGoogle']);

Route::apiResource('users', UserController::class);


