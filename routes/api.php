<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;


Route::middleware('auth:sanctum')->group(function (){

    Route::apiResource('chat', ChatController::class)->only(['index','store','show']);
    Route::apiResource('chat_message', MessageController::class)->only(['index','store']);

});