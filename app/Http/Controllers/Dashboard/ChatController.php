<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{

   public function index()
{
    $doctorId = Auth::user()->id; 

    $patients = Patient::whereHas('bookings', function ($query) use ($doctorId) {
        $query->where('doctor_id', $doctorId)
              ->where('status', 'Confirmed');
    })
    ->with('user') 
    ->get();

    return view('massenger', [
        'patients' => $patients,
    ]);
}

}
