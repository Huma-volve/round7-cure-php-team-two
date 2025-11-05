<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\otpController;
use App\Http\Controllers\UserController;
use App\Mail\SendResetPasswordEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
public function sendResetCode(Request $request){
    $otp=otpController::sendOTPCode($request);
    Mail::to($request->email)->queue(new SendResetPasswordEmail($otp));
}
public function resetPassword(Request $request)
{



$request->validate(['password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()]]);
$user=UserController::FindByEmail($request->email);
$user->password=Hash::make($request->password);
$user->otp_code=null;
$user->save();
return response(['message'=>'Password reset successfully.']);



}

}
