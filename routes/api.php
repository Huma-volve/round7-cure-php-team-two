<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('bookings', [BookingController::class, 'create']);
Route::get('bookings/{booking}', [BookingController::class, 'show']);
Route::put('bookings/{booking}/update', [BookingController::class, 'update']);
Route::delete('bookings/{booking}/cancel', [BookingController::class, 'destroy']);

Route::get('bookings/doctor', [BookingController::class, 'doctorBookings']);
Route::get('bookings/patient', [BookingController::class, 'patientBookings']);
