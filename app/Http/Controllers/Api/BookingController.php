<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\UpdateRequest;
use App\Models\Doctor;
use App\Models\Setting;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Booking::with('patient.user', 'doctor.user')->orderBy('booking_date', 'desc')->paginate(10);
        return view('dashboard.bookings.admin.index', compact('bookings'));
    }

    public function store(StoreRequest $request)
    {

        if (!Auth::user()->patient) {
            return response()->json(['message' => 'Only patients can make bookings'], 403);
        }

        $validated = $request->validated();

        $patient = Auth::user()->patient;
        $doctor=Doctor::find($validated['doctor_id']);
        $doctorAmount=$doctor->session_price;
        $settings=Setting::first();
        $rate=$settings->rate ?? 20;


        $booking = Booking::create(array_merge(
            $validated,
            [
                'patient_id' => $patient->id,
                'doctor_amount'=>$doctorAmount,
                'rate'=>$rate,
                'total'=>$doctorAmount + $rate,
            ]
        ));

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }

    public function show(Request $request, Booking $booking)
    {
        $booking->load('patient.user', 'doctor.user');
        if (Auth::user()->admin) {
            return view('dashboard.bookings.admin.show', compact('booking'));
        }
        if (Auth::user()->doctor) {
            return view('dashboard.bookings.doctor.show', compact('booking'));
        }
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
        $booking = Booking::findOrFail($id);

        $user = Auth::user();

        if (
            ($user->patient && $booking->patient_id === $user->patient->id) ||
            ($user->doctor && $booking->doctor_id === $user->doctor->id)
        ) {
            $booking->update(['status' => 'Cancelled']);
            return redirect()->back()->with('success', 'Booking cancelled successfully');
        }
        // Unauthorized
        return redirect()->back()->with('success', 'Un Authorized to cancel thisBooking');
    }

    public function patientBookings()
    {
        $patient = Auth::user()->patient;
        $bookings = $patient->bookings()->with('doctor.user')->get();
        return response()->json(['message' => "Patient bookings fetched successfully", 'data' => $bookings], 200);
    }

    public function doctorBookings()
    {
        $bookings = Auth::user()->doctor->bookings()->where('status', '!=', 'Cancelled')
            ->with('patient.user')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->paginate(10);

        return view('dashboard.bookings.doctor.index', compact('bookings'));
    }
}
