<?php

namespace App\Http\Controllers\Dashboard\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\User;
use App\Services\Reports\AnalyticsService;

class DashboardController extends Controller
{
    //
    // Dashboard main view
    public function index(Request $req, AnalyticsService $analytics)
    {
        $doctorId = auth()->user()->doctor->id;
        $from = $req->get('from');
        $to = $req->get('to');

        $totals = $analytics->getTotals($doctorId, $from, $to, true, true); // count patients ever
        $bookingsTrend = $analytics->bookingsTrend($doctorId, $from, $to, 'day', true);
        $revenueTrend = $analytics->revenueTrend($doctorId, $from, $to, 'day', true);
        $byStatus = $analytics->bookingsByStatus(null, $from, $to);

        return view('dashboard.doctor.dashboard', compact('totals', 'bookingsTrend', 'revenueTrend', 'byStatus'));
    }

    // Patients list
    public function patients(Request $request)
    {
        $doctor = $request->user()->doctor;

        $query = Patient::query()
            ->whereExists(function ($q) use ($doctor) {
                $q->select(DB::raw(1))
                    ->from('bookings')
                    ->whereColumn('bookings.patient_id', 'patients.id')
                    ->where('bookings.doctor_id', $doctor->id);
            });

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->whereHas('user', function ($qu) use ($q) {
                $qu->where('name', 'like', "%{$q}%")
                    ->orWhere('users.phone_number', 'like', "%{$q}%");
            });
        }

        $patients = $query->with('user')->paginate(15)->withQueryString();

        return view('doctor.patients.index', compact('patients'));
    }

    // Patient details
    public function showPatient(Request $request, Patient $patient)
    {
        $user = $request->user();

        // authorize using Gate defined earlier
        if (!\Gate::forUser($user)->allows('view-patient', $patient)) {
            abort(403);
        }

        $doctor = $user->doctor;

        // patient's bookings with this doctor
        $bookings = Booking::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->with(['doctor.user', 'patient.user'])
            ->orderByDesc('starts_at')
            ->get();

        // payments related to those bookings
        $payments = Payment::where('doctor_id', $doctor->id)
            ->whereIn('booking_id', $bookings->pluck('id')->toArray())
            ->get();

        return view('doctor.patients.show', compact('patient', 'bookings', 'payments'));
    }

    // optional: bookings list
    public function bookings(Request $request)
    {
        $doctor = $request->user()->doctor;
        $query = Booking::where('doctor_id', $doctor->id)->with(['patient.user'])->latest();
        $bookings = $query->paginate(20);
        return view('doctor.bookings.index', compact('bookings'));
    }

    // optional: payments list
    public function payments(Request $request)
    {
        $doctor = $request->user()->doctor;
        $query = Payment::where('doctor_id', $doctor->id)->latest();
        $payments = $query->paginate(20);
        return view('doctor.payments.index', compact('payments'));
    }
}
