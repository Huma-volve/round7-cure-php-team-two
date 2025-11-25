<?php

use App\Http\Controllers\Dashboard\doctor\availableTimeController;
use App\Http\Controllers\Dashboard\doctor\DoctorController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SettingController;
//use App\Http\Controllers\Dashboard\DoctorController;
use App\Http\Controllers\Dashboard\Doctor\DashboardController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');
/*

Route::middleware(['auth', 'role-check'])->get('/', function () {
    return "hello patient";
})->name('dashboard');
 */

//all bookings
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

Route::get('/bookings/{booking}/show', [BookingController::class, 'show'])->name('bookings.show');

//doctor bookings
Route::get('doctor/bookings', [BookingController::class, 'doctorBookings'])->name('doctor.bookings.index');
Route::delete('doctor/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('doctor.bookings.cancel');
//cancel booking
Route::delete('doctor/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');



Route::resource('questions', QuestionController::class)->names([
    'index' => 'questions.index',
    'create' => 'questions.create',
    'store' => 'questions.store',
    'show' => 'questions.view',
    'edit' => 'questions.edit',
    'update' => 'questions.update',
    'destroy' => 'questions.delete',
]);
;
//Route::resource('settings', SettingController::class);
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings/update/', [SettingController::class, 'update'])->name('settings.update');



Route::middleware(['auth', 'can:isDoctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Route::get('/patients', [DashboardController::class, 'patients'])->name('patients.index');
    //Route::get('/patients/{patient}', [DashboardController::class, 'showPatient'])->name('patients.show');
    //Route::get('/bookings', [DashboardController::class, 'bookings'])->name('bookings.index'); // optional
    //Route::get('/payments', [DashboardController::class, 'payments'])->name('payments.index'); // optional
});



require __DIR__ . '/auth.php';
require __DIR__ . '/doctor.php';
require __DIR__ . '/admin.php';
