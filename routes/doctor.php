<?php

use App\Http\Controllers\Dashboard\doctor\availableTimeController;
use App\Http\Controllers\Dashboard\doctor\DoctorController;

Route::middleware(['auth', 'role:doctor'])->prefix('dashboard/doctor')->name('doctor.')->group(function () {


    Route::controller(DoctorController::class)->group(function () {
        Route::get('/profile', 'view')->name('profile');
        Route::patch('/profile', 'update')->name('profile.update');

    });





    Route::controller(availableTimeController::class)->group(
        function () {
            Route::get('/available-time', 'view')->name('available-time');
            Route::post('/add-slot', 'add')->name('add_slot');

            Route::delete('/delete-slot', 'destroy')->name('delete_slot');

            // Route for Updating a slot
            Route::patch('/update-slot', 'update')->name('update_slot');
        }
    );

});
