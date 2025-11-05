<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class otpController extends Controller
{
    public static function makeOtp()
    {

       return $otp = random_int(100000, 999999);

    }
    public static function storeUserOtp(User $user,$otp){
        $user->otp_code=$otp;
        $user->otp_expire=now()->addMinutes(10);
        $user->save();
    }

    public static function sendOTPCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user=User::where('email',$request->email)->first();
        $otp=otpController::makeOtp();
        otpController::storeUserOtp($user,$otp);
        return $otp;



    }

    public static function verifyOtp(Request $request)
    {
        $response=fn($message)=>response()->json([
            'message'=>$message,
        ],200);
      $user=User::where('otp_code',$request->otp)->first();

      if(!$user){
          return $response('invalid code , please try again');
      }

        $time_diff=DateController::gettDiffInMinutes($user->otp_expire,Carbon::now());
      if($time_diff>10)
      {
          return $response('expired code , please try again');
      }
      return true;
    }
}
