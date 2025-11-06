<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');

