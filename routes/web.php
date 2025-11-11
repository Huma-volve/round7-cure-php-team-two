<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\DoctorController;

Route::get('/', function () {

    return redirect()->route('login');
})->name('dashboard');

Route::get('/register', function () {
    return view('dashboard.auth.login');
})->name('register');

Route::get('/login', function () {
    return view('dashboard.auth.login');
});
Route::get('/forgot-password', function () {
    return view('dashboard.auth.forgot-password');
})->name('password.request');

Route::get('/doctors', fn() => 'Payment successful!')->name('doctors.index');
Route::get('/doctors/create', fn() => 'Payment successful!')->name('doctors.create');
Route::get('/specialties', fn() => 'Payment successful!')->name('specialties.index');
Route::get('/bookings', fn() => 'Payment successful!')->name('bookings.index');
Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');



Route::resource('questions', QuestionController::class);
Route::resource('settings', SettingController::class);



Route::middleware('auth')->prefix('/dashboard')->group(
    function () {
        Route::get('doctor', function () {
            $doctor = new DoctorController();
            return $doctor->show();

        })->middleware('role:doctor');

        Route::get('admin', function () {
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

/* Route::middleware(['auth', 'can:isDoctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/patients', [DashboardController::class, 'patients'])->name('patients.index');
    Route::get('/patients/{patient}', [DashboardController::class, 'showPatient'])->name('patients.show');
    Route::get('/bookings', [DashboardController::class, 'bookings'])->name('bookings.index'); // optional
    Route::get('/payments', [DashboardController::class, 'payments'])->name('payments.index'); // optional
}); */
require __DIR__ . '/auth.php';
//require __DIR__ . '/doctor.php';
