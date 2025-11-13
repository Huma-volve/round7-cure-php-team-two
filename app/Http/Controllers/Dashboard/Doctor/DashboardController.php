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

class DashboardController extends Controller
{
    //
    // Dashboard main view
    public function index(Request $request)
    {
        $user = $request->user();
        $doctor = $user->doctor; // assumes hasOne relation

        // default range: last 30 days
        $to = Carbon::now();
        $from = $request->filled('from') ? Carbon::parse($request->from) : $to->copy()->subDays(29);

        // 1) bookings count in range
        $bookingsQuery = Booking::where('doctor_id', $doctor->id)
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()]);

        $bookingsCount = (int) $bookingsQuery->count();

        // 2) revenue sum in range (payments)
        $revenue = (float) Payment::where('doctor_id', $doctor->id)
            ->whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->where('status', 'paid')
            ->sum('amount');

        // 3) unique patients count (ever)
        $patientsCount = Booking::where('doctor_id', $doctor->id)
            ->distinct('patient_id')->count('patient_id');

        // 4) bookings per day for chart (group by date)
        $period = $from->copy();
        // build labels and values arrays
        $labels = [];
        $values = [];
        $days = $from->diffInDays($to) + 1;
        for ($i = 0; $i < $days; $i++) {
            $d = $from->copy()->addDays($i);
            $labels[] = $d->format('Y-m-d');
            $values[$d->format('Y-m-d')] = 0;
        }

        $agg = Booking::select(DB::raw("DATE(starts_at) as day"), DB::raw("COUNT(*) as cnt"))
            ->where('doctor_id', $doctor->id)
            ->whereBetween('starts_at', [$from->startOfDay(), $to->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        foreach ($agg as $row) {
            $values[$row->day] = (int) $row->cnt;
        }

        $series = array_values($values);

        // 5) last 10 bookings
        $latestBookings = Booking::with(['patient.user'])->where('doctor_id', $doctor->id)
            ->latest('starts_at')->limit(10)->get();

        // 6) latest payments
        $latestPayments = Payment::where('doctor_id', $doctor->id)
            ->latest('paid_at')->limit(10)->get();

        return view('doctor.dashboard', compact(
            'from',
            'to',
            'bookingsCount',
            'revenue',
            'patientsCount',
            'labels',
            'series',
            'latestBookings',
            'latestPayments'
        ));
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
