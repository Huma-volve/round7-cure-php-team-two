<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.analytics');
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
Route::get('/bookings', fn() => 'Payment successful!')->name('bookings.index');
Route::get('/success', fn() => 'Payment successful!')->name('stripe.success');
Route::get('/cancel', fn() => 'Payment canceled.')->name('stripe.cancel');

