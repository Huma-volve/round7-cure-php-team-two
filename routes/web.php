<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SettingController;
use  App\Http\Controllers\Dashboard\DoctorController;
use App\Http\Controllers\Api\BookingController;


Route::get('/', function () {

    return redirect()->route('login');
})->name('dashboard');

Route::get('/register', function () {
    return view('dashboard.auth.login');
})->name('register');

Route::get('/login', function () {
    return view('dashboard.auth.login');
})->name('login');
Route::get('/forgot-password', function () {
    return view('dashboard.auth.forgot-password');
})->name('password.request');

Route::get('/doctors', fn() => 'Payment successful!')->name('doctors.index');
Route::get('/doctors/create', fn() => 'Payment successful!')->name('doctors.create');
Route::get('/specialties', fn() => 'Payment successful!')->name('specialties.index');
//all bookings
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

Route::put('bookings/{booking}/update', [BookingController::class, 'update'])->name('dashboard.bookings.edit');
Route::delete('bookings/{booking}/cancel', [BookingController::class, 'destroy'])->name('dashboard.bookings.delete');
//doctor bookings
Route::get('doctor/bookings', [BookingController::class, 'doctorBookings']);
//cancel booking
Route::delete('doctor/bookings/{booking}/cancel', [BookingController::class, 'destroy']);
Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');



    Route::resource('questions', QuestionController::class);
    Route::resource('settings', SettingController::class);



Route::middleware('auth')->prefix('/dashboard')->group(
   function()
   {
      Route::get('doctor',function()
      {
          $doctor=new DoctorController();
          return $doctor->show();

      })->middleware('role:doctor');

      Route::get('admin',function()
      {
//          return view('dashboard');
             return "this is admin";


      })->middleware('role:admin');
   }
);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
