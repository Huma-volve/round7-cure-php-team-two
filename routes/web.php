<?php

use App\Http\Controllers\Dashboard\doctor\availableTimeController;
use App\Http\Controllers\Dashboard\doctor\DoctorController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\ChatController;
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
/* Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

Route::get('/doctors', fn() => 'Payment successful!')->name('doctors.index');
Route::get('/doctors/create', fn() => 'Payment successful!')->name('doctors.create');
Route::get('/specialties', fn() => 'Payment successful!')->name('specialties.index');

 */
//doctor bookings
//Route::get('doctor/bookings', [BookingController::class, 'doctorBookings'])->name('doctor.bookings.index');
//Route::delete('doctor/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('doctor.bookings.cancel');
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

//Route::resource('settings', SettingController::class);
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings/update/', [SettingController::class, 'update'])->name('settings.update');




Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Route::get('/patients', [DashboardController::class, 'patients'])->name('patients.index');
    //Route::get('/patients/{patient}', [DashboardController::class, 'showPatient'])->name('patients.show');
    //Route::get('/bookings', [DashboardController::class, 'bookings'])->name('bookings.index'); // optional
    //Route::get('/payments', [DashboardController::class, 'payments'])->name('payments.index'); // optional
});
Route::middleware(['auth', 'role-check'])->get('/', function () {
    return "hello patient";
})->name('dashboard');

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');


Route::middleware('auth')->prefix('/dashboard')->group(function () {
    //doctor panel
    Route::middleware('role:doctor')->prefix('/doctor')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('doctor-dashboard');

        //doctor bookings
        Route::get('bookings', [BookingController::class, 'doctorBookings'])->name('doctor.bookings.index');
        //cancel booking
        Route::delete('bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('doctor.bookings.cancel');
        //show single booking details
        Route::get('/bookings/{booking}/show', [BookingController::class, 'show'])->name('doctor.bookings.show');
    });

    //admin panel
    Route::middleware('role:admin')->prefix('/admin')->group(function () {
        Route::get('/', function () {
            return view('dashboard.Admin.index');
        })->name('admin-dashboard');

        //admin bookings page
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        //show single booking details
        Route::get('/bookings/{booking}/show', [BookingController::class, 'show'])->name('bookings.show');
    });

});




Route::middleware('auth')->prefix('/dashboard')->group(
    function () {
        Route::get('/doctor', [DoctorController::class, 'index'])->middleware('role:doctor')
            ->name('doctor-dashboard');

        Route::get('/admin', function () {

            return view('dashboard.Admin.index');


        })->middleware('role:admin|helper')->name('admin.dashboard');
    }
);




require __DIR__ . '/auth.php';
require __DIR__ . '/doctor.php';
require __DIR__ . '/admin.php';
Route::get('dashboard/{id?}', [ChatController::class, 'index'])
    ->middleware('auth')
    ->name('messenger');
