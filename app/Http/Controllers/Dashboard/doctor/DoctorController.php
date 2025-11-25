<?php

namespace App\Http\Controllers\Dashboard\doctor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Files\FileController;
use App\Http\Controllers\Files\ImageController;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        return view('dashboard.Doctor.index');
    }
    public static function doctor()
    {
        return Auth::user()->doctor;
    }
    public function show()
    {
        return Auth::user()->load('doctor');
    }
    public function view()
    {
        $doctor = $this->show();

        return view('dashboard.Doctor.profile', ['doctor' => $doctor]);
    }
    public function add(Request $request)
    {
        $data = $request->validate(['']);
    }
    public static function update(UpdateDoctorRequest $request)
    {


        $doctor = Auth::user();
        $data = $request->validated();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        $data['profile_photo'] = ImageController::update_user_image($request, $doctor);


        $doctor->update($data);
        return redirect()->route('doctor.profile');



    }
}
