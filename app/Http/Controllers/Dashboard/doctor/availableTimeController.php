<?php

namespace App\Http\Controllers\Dashboard\doctor;

use App\Http\Controllers\Api\DateController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class availableTimeController extends Controller
{
 private const REDIRECTED_ROUTE='doctor.available-time';

// public static function show()
// {
//    $doctor=self::doctor();
//    return view('dashboard.doctor');

// }
// public static function show(){
    // return DoctorController::doctor()->available_slots;

//}
public  function show(){

    $available_time= DoctorController::doctor()->available_slots;
    if(!is_array($available_time)){return json_decode($available_time);}
    return $available_time;

}
public  function view(){
    $availableTimes=self::show();

    return view('dashboard.Doctor.available-time',['availableTimes'=>$availableTimes]);
}
public function add(Request $request)
{
    $request->validate(['time'=>'required']);
    $isValid=DateController::IsValid(request('time'));
    if(!$isValid){
        return redirect()->back()->withErrors(['time'=>'pick up a valid time']);
    }
    $doctor=DoctorController::doctor();
    $time=$this->show();
    $time[]=request('time');
    $doctor->available_slots=$time;
    $doctor->save();
   return redirect()->route(self::REDIRECTED_ROUTE);
}
public function update(Request $request)
{

    $isValid=DateController::IsValid(request('new_time'));

    if (!$isValid) {
        return redirect()->back()
            ->withErrors(['new_time' => 'pick a valid date']);

    }
        $data=$request->validate(['old_time'=>['required'],'new_time'=>'required']);
        $doctor=DoctorController::doctor();
        $time=$this->show();
        $key=array_search(request('old_time'),$time);
        if($key!==false){
            $time[$key]=$data['new_time'];
        }
        $doctor->available_slots=$time;
        $doctor->save();
        return redirect()->route(self::REDIRECTED_ROUTE);


}
public function destroy(Request $request)
{
 $doctor=DoctorController::doctor();
 $time=$this->show();
 $key=array_search(request('time'),$time);
 if($key!==false){
     unset($time[$key]);
     $doctor->available_slots=$time;
     $doctor->save();
     return redirect()->route(self::REDIRECTED_ROUTE);
 }
 return redirect()->back();

}
}
