<?php
namespace App\Http\Controllers\Dashboard\admin;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\HelperController;
use App\Http\Controllers\Files\FileController;
use App\Http\Controllers\Files\ImageController;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Services\Reports\AnalyticsService;
use function Pest\Laravel\instance;


class AdminController extends Controller
{
    public function index(Request $request, AnalyticsService $analytics)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $totals = $analytics->getTotals(null, $from, $to, false, true);
        $bookingsTrend = $analytics->bookingsTrend(null, $from, $to, 'day', true);
        $revenueTrend = $analytics->revenueTrend(null, $from, $to, 'day', true);
        $topDoctors = $analytics->topDoctorsByEarnings(5, $from, $to);
        $byStatus = $analytics->bookingsByStatus(null, $from, $to);

        return view('dashboard.admin.dashboard', compact('totals', 'bookingsTrend', 'revenueTrend', 'topDoctors', 'byStatus', 'from', 'to'));
    }

    public function viewDoctors()
    {
        $doctors = Doctor::with(['user', 'specialty'])->paginate('10');
        return view('dashboard.Admin.Doctors.View', compact('doctors'));
    }


    public function editDoctor(User $user)
    {

        $user->load('doctor');


        $specialties = Specialty::all();


        return view('dashboard.Admin.Doctors.Edit', compact('user', 'specialties'));
    }

    public function updateDoctor(UpdateDoctorRequest $request, User $user)
    {

        $doctor = $user->doctor;
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }



        $data['profile_photo']=ImageController::update_user_image($request,$user);

        //        if (!$request->hasFile('image') && $request->input('remove_image') == 1) {
//            FileController::deleteFile($user->profile_photo, 'images/users');
//            $data['profile_photo'] = null;
//        }
//
//        if ($request->hasFile('image')) {
//            $image = FileController::updateFile($request->file('image'), $user->profile_photo, 'images/users');
//            $data['profile_photo'] = $image;
//        }
        $data['profile_photo'] = ImageController::update_user_image($request, $user);


        $user->update($data);
        $doctor->update($request->only(['specialty_id', 'license_number', 'session_price']));


        return redirect()->route('admin.doctor.index')->with('success', 'Doctor updated successfully');
    }

    public function destroyDoctor($id)
    {
        $user = User::withTrashed()->find($id);
        $doctor = $user->doctor;


        FileController::deleteFile($user->profile_picture, 'images/users');
        $doctor->reviews()->Forcedelete();
        $doctor->bookings()->forceDelete();
        $doctor->Forcedelete();

        return redirect()->route('admin.doctor.index');
    }
    public function addDoctorView()
    {
        return view('dashboard.Admin.Doctors.Add', ['specialties' => Specialty::all()]);
    }
    public function AddDoctor(StoreUserRequest $request)
    {
        $data = $request->validate([
            'specialty_id' => 'required',
            'license_number' => 'required|string|max:50|unique:doctors,license_number',
            'session_price' => 'required|numeric|min:0',
        ]);


   $user=UserController::store($request);
   $doctor= $user->doctor()->create($data);
   $user->assignRole('doctor');
   return redirect()->route('admin.doctor.index');

}
public function ViewHelpers()
{
        $helpers= HelperController::GetHelpers();
        $helpers->load('user');
        return view('dashboard.Admin.Helpers.View',compact('helpers'));
}
public function AddHelperView(Admin $helper)
{

        return view('dashboard.Admin.Helpers.Add',compact('helper'));
}
public function AddHelper(StoreUserRequest $request)
{

       $helper= HelperController::storeHelper($request);

       return redirect()->route('admin.helper.index');
}
public function EditHelper(User $user){
        return view('dashboard.Admin.Helpers.Edit',compact('user'));
}

public function UpdateHelper(UpdateUserRequest $request,User $user)
{
        $helper=HelperController::updateHelper($request,$user);
    return redirect()->route('admin.helper.index');
}
public function DestroyHelper(User $helper)
{
     HelperController::DeleteHelper($helper);
    return redirect()->route('admin.helper.index');
}

//        $user = UserController::store($request);
//        $doctor = $user->doctor()->create($data);
//        $user->assignRole('doctor');
//        return redirect()->route('admin.doctor.index');
//
//    }

}
