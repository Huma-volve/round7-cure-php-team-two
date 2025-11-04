<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('auth')->controller(PasswordController::class)->group(function () {
    Route::post('forgot-password',  'sendResetCode');

// Endpoint لإدخال الكود والباسورد الجديد
    Route::post('reset-password',  'resetPassword');
});


Route::post('google/login', [GoogleController::class, 'LogInWithGoogle']);

Route::apiResource('users', UserController::class);

