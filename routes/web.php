<?php

use App\Http\Controllers\Dashboard\doctor\availableTimeController;
use App\Http\Controllers\Dashboard\doctor\DoctorController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Support\Facades\Route;


Route::get('/doctors', fn() => 'Payment successful!')->name('doctors.index');
Route::get('/doctors/create', fn() => 'Payment successful!')->name('doctors.create');
Route::get('/specialties', fn() => 'Payment successful!')->name('specialties.index');


Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');



    Route::resource('questions', QuestionController::class);
    Route::resource('settings', SettingController::class);

Route::get('/',function(){
    return redirect()->route('login');
})->middleware('guest');


Route::middleware(['auth','role-check'])->get('/',function(){
    return "hello patient";
})->name('dashboard');



Route::middleware('auth')->prefix('/dashboard')->group(function(){
    //doctor panel
    Route::middleware('role:doctor')->prefix('/doctor')->group(function(){
        Route::get('/',[DoctorController::class,'index'])->name('doctor-dashboard');

        //doctor bookings
        Route::get('bookings', [BookingController::class, 'doctorBookings'])->name('doctor.bookings');
        //cancel booking
        Route::delete('bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('doctor.bookings.cancel');
        //show single booking details
        Route::get('/bookings/{booking}/show', [BookingController::class, 'show'])->name('bookings.show');
    });

    //admin panel
    Route::middleware('role:admin')->prefix('/admin')->group(function(){
        Route::get('/',function()
        {
            return view('dashboard.Admin.index');
        })->name('admin-dashboard');

        //admin bookings page
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        //show single booking details
        Route::get('/bookings/{booking}/show', [BookingController::class, 'show'])->name('bookings.show');
    });

});






require __DIR__.'/auth.php';
require __DIR__.'/doctor.php';
require __DIR__.'/admin.php';
