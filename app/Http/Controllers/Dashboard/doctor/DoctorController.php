<?php

namespace App\Http\Controllers\Dashboard\doctor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Files\FileController;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public static  function doctor()
    {
        return Auth::user()->doctor;
    }
    public function show()
    {
        return Auth::user()->load('doctor');
    }
    public function view()
    {
        $doctor=$this->show();

        return view('dashboard.Doctor.profile',['doctor' => $doctor]);
    }
    public function update(UpdateUserRequest $request)
    {

        $doctor=Auth::user();
        $data=$request->validated();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if(!$request->hasFile('profile_image')&&$request->input('remove_image')==1){
            FileController::deleteFile($doctor->profile_photo,'images/users');
            $data['profile_photo']=null;

        }
        if($request->hasFile('profile_image')){
        $image=FileController::updateFile($request->file('profile_image'),$doctor->profile_photo,'images/users');
        $data['profile_photo']=$image;}

        $doctor->update($data);
        return redirect()->route('doctor.profile');



    }
}
