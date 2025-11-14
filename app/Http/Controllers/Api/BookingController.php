<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\UpdateRequest;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:make booking', ['only' => ['store']]);
        $this->middleware('permission:reschedule booking', ['only' => ['reschedule']]);
        $this->middleware('permission:cancel booking', ['only' => ['cancel']]);
    }

    public function index(){
        $bookings=Booking::with('patient.user','doctor.user')->orderBy('booking_date','desc')->get();
        return view('dashboard.bookings.index',compact('bookings'));
    }

    public function store(StoreRequest $request)
    {

        if (!Auth::user()->patient) {
            return response()->json(['message' => 'Only patients can make bookings'], 403);
        }

        $validated=$request->validated();

        $patient = Auth::user()->patient;

        $booking = Booking::create(array_merge(
            $validated,
            ['patient_id' => $patient->id]
        ));

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }

    public function show(Request $request,Booking $booking)
    {
        return view('dashboard.bookings.show',compact('booking'));
    }

    public function reschedule(UpdateRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        if (
            (!$user->patient || $booking->patient_id !== $user->patient->id)
        ) {
            return response()->json(['message' => 'Unauthorized to update this booking'], 403);
        }

        $validated = $request->validated();

        $booking->update(array_merge(
            $validated,
            ['status' => 'rescheduled']
        ));

        return response()->json([
            'message' => 'Booking Rescheduled successfully',
            'data' => $booking,
        ], 200);
    }

    public function cancel($id)
    {
        $booking=Booking::findOrFail($id);

        $user=Auth::user();

        if (
            ($user->patient && $booking->patient_id === $user->patient->id) ||
            ($user->doctor && $booking->doctor_id === $user->doctor->id)
        ) {
            $booking->update(['status' => 'canceled']);
            return response()->json(['message' => 'Booking canceled successfully'], 200);
        }
        // Unauthorized
        return response()->json(['message' => 'Unauthorized to cancel this booking'], 403);
    }

    public function patientBookings()
    {
        $patient = Auth::user()->patient;
        $bookings = $patient->bookings()->with('doctor.user')->get();
        return response()->json(['message'=>"Patient bookings fetched successfully",'data'=>$bookings],200);
    }

    public function doctorBookings()
    {
        $bookings = Auth::user()->doctor->bookings()
            ->with('patient.user')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

            return response()->json(['message'=>"Doctor bookings fetched successfully",'data'=>$bookings],200);
    }
}
