<?php

use App\Http\Controllers\Dashboard\doctor\availableTimeController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Auth\AuthController;

use Illuminate\Support\Facades\Route;

Route::get('/',function(){
        return redirect()->route('login');
    })->middleware('guest');



   Route::middleware(['auth','role-check'])->get('/',function(){
       return "hello patient";
   })->name('dashboard');



Route::get('/doctors', fn() => 'Payment successful!')->name('doctors.index');
Route::get('/doctors/create', fn() => 'Payment successful!')->name('doctors.create');
Route::get('/specialties', fn() => 'Payment successful!')->name('specialties.index');
Route::get('/bookings', fn() => 'Payment successful!')->name('bookings.index');
Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');



Route::middleware('auth')->prefix('dashboard')->group(
   function()
   {

      Route::get('doctor',[availableTimeController::class,'view'])->middleware('role:doctor')->name('doctor-dashboard');
      Route::get('admin-dashboard',[HomeController::class,'index'])->middleware('role:admin')->name('admin-dashboard');
    //   Route::get('messenger',[ChatController::class,'index']);

    Route::resource('questions', QuestionController::class);
    Route::resource('settings', SettingController::class);


}
);


require __DIR__.'/auth.php';
require __DIR__.'/doctor.php';
Route::get('dashboard/{id?}', [ChatController::class, 'index'])
  ->middleware('auth')
   ->name('messenger');

