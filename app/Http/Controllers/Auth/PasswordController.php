<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\otpController;
use App\Mail\SendResetPasswordEmail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
public function sendResetPassword(Request $request){
    otpController::sendOTPCode($request);
}
public function verifyOTP(Request $request){
   return otpController::verifyOTP($request);
}
public function resetPassword(Request $request)
{

$request->validate(['password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()]]);


}

}
