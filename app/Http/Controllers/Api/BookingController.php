<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:reschedule booking', ['only' => ['update']]);
        $this->middleware('permission:cancel booking', ['only' => ['destroy']]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i:s',
            'payment_method' => 'required|in:PayPal,Stripe,Cash',
        ]);

        $patient = Auth::user()->patient;

        $booking = Booking::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'payment_method' => $request->payment_method,
        ]);
        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }

    public function show(Request $request,Booking $booking)
    {
        return response()->json([
            'message' => 'Booking fetched successfully',
            'data' => $booking,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        if (!$user->patient || $booking->patient_id !== $user->patient->id) {
            return response()->json(['message' => 'Unauthorized to update this booking'], 403);
        }

        $request->validate([
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i:s',
        ]);

        $booking->update([
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
        ]);

        return response()->json([
            'message' => 'Booking updated successfully',
            'data' => $booking,
        ], 200);
    }


    public function destroy(Request $request,$id)
    {
        $booking=Booking::where('id',$id)->findOrFail();

        $user=Auth::user();

        if (
            ($user->patient && $booking->patient_id === $user->patient->id) ||
            ($user->doctor && $booking->doctor_id === $user->doctor->id)
        ) {
            $booking->delete();
            return response()->json(['message' => 'Booking canceled successfully'], 200);
        }
        // Unauthorized
        return response()->json(['message' => 'Unauthorized to cancel this booking'], 403);
    }

    public function patientBookings()
    {
        $bookings = Auth::user()->patient->bookings()->with('doctor.user')->get();
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
