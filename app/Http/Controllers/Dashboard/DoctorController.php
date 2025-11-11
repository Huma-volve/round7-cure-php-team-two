<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
public static  function doctor()
{
    return Auth::user()->doctor()->get();
}
//public static function show()
//{
//    $doctor=self::doctor();
//    return view('dashboard.doctor');
//}
public static function getTime(){
    return self::doctor();
}
public static function available_time(){
    $available_time=self::getTime();
    return view('dashboard.Doctor.available-time',['available_time'=>$available_time]);
}
}
