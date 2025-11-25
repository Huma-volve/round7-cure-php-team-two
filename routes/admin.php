<?php

use App\Http\Controllers\Dashboard\admin\AdminController;

Route::prefix('dashboard/admin')->controller(AdminController::class)->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', 'index')->name('admin.dashboard');
    Route::name('admin.doctor.')->group(function () {
        Route::get('/view-doctor', 'viewDoctors')->name('index');
        Route::get('/add-doctor', 'addDoctorView')->name('add-page');
        Route::post('/add-doctor', 'AddDoctor')->name('add');
        Route::get('/edit-doctor/{user}', 'editDoctor')->name('update-page');
        Route::patch('/edit-doctor/{user}', 'updateDoctor')->name('update');
        Route::delete('/delete-doctor/{user}', 'destroyDoctor')->name('delete');

    });

});
