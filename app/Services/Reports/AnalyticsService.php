<?php

namespace App\Services\Reports;

use App\Models\Booking;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * AnalyticsService (Bookings-only)
 *
 * Uses `bookings` table for payments and booking analytics.
 */
class AnalyticsService
{
    protected function normalizeDates($from, $to, int $defaultDays = 30): array
    {
        $tz = config('app.timezone', 'UTC');

        if ($from instanceof Carbon) {
            $fromDate = $from->copy()->startOfDay();
        } elseif (!empty($from)) {
            $fromDate = Carbon::parse($from)->startOfDay()->timezone($tz);
        } else {
            $fromDate = null;
        }

        if ($to instanceof Carbon) {
            $toDate = $to->copy()->endOfDay();
        } elseif (!empty($to)) {
            $toDate = Carbon::parse($to)->endOfDay()->timezone($tz);
        } else {
            $toDate = null;
        }

        if (!$fromDate && !$toDate) {
            $toDate = Carbon::now()->endOfDay()->timezone($tz);
            $fromDate = $toDate->copy()->subDays($defaultDays - 1)->startOfDay();
        } elseif (!$fromDate) {
            $fromDate = (clone $toDate)->subDays($defaultDays - 1)->startOfDay();
        } elseif (!$toDate) {
            $toDate = (clone $fromDate)->addDays($defaultDays - 1)->endOfDay();
        }

        if ($fromDate->gt($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
        }

        return [$fromDate, $toDate];
    }

    /**
     * getTotals using bookings as source of payments.
     *
     * @param int|null $doctorId
     * @param mixed $from
     * @param mixed $to
     * @param bool $patientsUniqueEver
     * @param bool $useCache
     * @return array
     */
    public function getTotals(?int $doctorId = null, $from = null, $to = null, bool $patientsUniqueEver = false, bool $useCache = false): array
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        if ($useCache) {
            $key = "reports:totals:booking:{$doctorId}:{$fromDate->toDateString()}:{$toDate->toDateString()}";
            return Cache::remember($key, 60, function () use ($doctorId, $fromDate, $toDate, $patientsUniqueEver) {
                return $this->computeTotals($doctorId, $fromDate, $toDate, $patientsUniqueEver);
            });
        }

        return $this->computeTotals($doctorId, $fromDate, $toDate, $patientsUniqueEver);
    }

    protected function computeTotals(?int $doctorId, Carbon $fromDate, Carbon $toDate, bool $patientsUniqueEver): array
    {
        $bookingsQ = Booking::query()
            ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()]);

        if ($doctorId)
            $bookingsQ->where('doctor_id', $doctorId);

        $totalBookings = (int) $bookingsQ->count();

        // payments count (bookings with payment_status = 'Paid' and payment_time in range)
        $paymentsQ = Booking::query()
            ->where('payment_status', 'Paid')
            ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()]);

        if ($doctorId)
            $paymentsQ->where('doctor_id', $doctorId);

        $totalPayments = (int) $paymentsQ->count();

        // earnings: sum of doctor_amount from paid bookings (use doctor_amount if present, fallback to total)
        $totalEarnings = (float) Booking::query()
            ->where('payment_status', 'Paid')
            ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->select(DB::raw('COALESCE(SUM(COALESCE(doctor_amount, total)),0) as total'))
            ->value('total');

        // unique patients
        if ($patientsUniqueEver && $doctorId) {
            $uniquePatients = (int) Booking::where('doctor_id', $doctorId)->distinct('patient_id')->count('patient_id');
        } else {
            $uniquePatients = (int) Booking::query()
                ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()])
                ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
                ->distinct('patient_id')->count('patient_id');
        }

        return [
            'total_bookings' => $totalBookings,
            'total_payments' => $totalPayments,
            'total_earnings' => round($totalEarnings, 2),
            'unique_patients' => $uniquePatients,
            'range' => ['from' => $fromDate->toDateString(), 'to' => $toDate->toDateString()],
        ];
    }

    /**
     * bookingsTrend grouped by booking_date (day) or month.
     */
    public function bookingsTrend(?int $doctorId = null, $from = null, $to = null, string $groupBy = 'day', bool $useCache = false): array
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);
        if ($useCache) {
            $key = "reports:bookingsTrend:booking:{$doctorId}:{$fromDate->toDateString()}:{$toDate->toDateString()}:{$groupBy}";
            return Cache::remember($key, 60, fn() => $this->computeBookingsTrend($doctorId, $fromDate, $toDate, $groupBy));
        }
        return $this->computeBookingsTrend($doctorId, $fromDate, $toDate, $groupBy);
    }

    protected function computeBookingsTrend(?int $doctorId, Carbon $fromDate, Carbon $toDate, string $groupBy): array
    {
        if ($groupBy === 'month') {
            $periods = [];
            $current = $fromDate->copy()->startOfMonth();
            while ($current->lte($toDate)) {
                $periods[$current->format('Y-m')] = 0;
                $current->addMonth();
            }

            $rows = Booking::select(DB::raw("DATE_FORMAT(booking_date, '%Y-%m') as period"), DB::raw('COUNT(*) as cnt'))
                ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()])
                ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
                ->groupBy('period')->orderBy('period')->get();

            foreach ($rows as $r)
                $periods[$r->period] = (int) $r->cnt;

            return ['labels' => array_keys($periods), 'series' => array_values($periods)];
        }

        // day grouping
        $days = [];
        $d = $fromDate->copy();
        while ($d->lte($toDate)) {
            $days[$d->format('Y-m-d')] = 0;
            $d->addDay();
        }

        $rows = Booking::select(DB::raw('DATE(booking_date) as day'), DB::raw('COUNT(*) as cnt'))
            ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->groupBy('day')->orderBy('day')->get();

        foreach ($rows as $r)
            $days[$r->day] = (int) $r->cnt;

        return ['labels' => array_keys($days), 'series' => array_values($days)];
    }

    /**
     * paymentsSum now reads bookings where payment_status = 'Paid' and payment_time in range.
     */
    public function paymentsSum(?int $doctorId = null, $from = null, $to = null, bool $onlyPaid = true, bool $useCache = false): array
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        if ($useCache) {
            $key = "reports:paymentsSum:booking:{$doctorId}:{$fromDate->toDateString()}:{$toDate->toDateString()}:" . ($onlyPaid ? 1 : 0);
            return Cache::remember($key, 60, fn() => $this->computePaymentsSum($doctorId, $fromDate, $toDate, $onlyPaid));
        }

        return $this->computePaymentsSum($doctorId, $fromDate, $toDate, $onlyPaid);
    }

    protected function computePaymentsSum(?int $doctorId, Carbon $fromDate, Carbon $toDate, bool $onlyPaid): array
    {
        $q = Booking::query()
            ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId));

        if ($onlyPaid)
            $q->where('payment_status', 'Paid');

        $count = (int) $q->count();
        $sum = (float) $q->select(DB::raw('COALESCE(SUM(COALESCE(doctor_amount, total)),0) as s'))->value('s');

        return ['count' => $count, 'sum' => round($sum, 2)];
    }

    /**
     * revenueTrend uses payment_time and sums doctor_amount (or total) per period.
     */
    public function revenueTrend(?int $doctorId = null, $from = null, $to = null, string $groupBy = 'day', bool $useCache = false): array
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        if ($useCache) {
            $key = "reports:revenueTrend:booking:{$doctorId}:{$fromDate->toDateString()}:{$toDate->toDateString()}:{$groupBy}";
            return Cache::remember($key, 60, fn() => $this->computeRevenueTrend($doctorId, $fromDate, $toDate, $groupBy));
        }

        return $this->computeRevenueTrend($doctorId, $fromDate, $toDate, $groupBy);
    }

    protected function computeRevenueTrend(?int $doctorId, Carbon $fromDate, Carbon $toDate, string $groupBy): array
    {
        if ($groupBy === 'month') {
            $periods = [];
            $current = $fromDate->copy()->startOfMonth();
            while ($current->lte($toDate)) {
                $periods[$current->format('Y-m')] = 0.0;
                $current->addMonth();
            }

            $rows = Booking::select(DB::raw("DATE_FORMAT(payment_time, '%Y-%m') as period"), DB::raw("COALESCE(SUM(COALESCE(doctor_amount, total)),0) as total"))
                ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
                ->where('payment_status', 'Paid')
                ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
                ->groupBy('period')->orderBy('period')->get();

            foreach ($rows as $r)
                $periods[$r->period] = (float) $r->total;
            return ['labels' => array_keys($periods), 'series' => array_values($periods)];
        }

        // day grouping
        $days = [];
        $d = $fromDate->copy();
        while ($d->lte($toDate)) {
            $days[$d->format('Y-m-d')] = 0.0;
            $d->addDay();
        }

        $rows = Booking::select(DB::raw('DATE(payment_time) as day'), DB::raw('COALESCE(SUM(COALESCE(doctor_amount, total)),0) as total'))
            ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->where('payment_status', 'Paid')
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->groupBy('day')->orderBy('day')->get();

        foreach ($rows as $r)
            $days[$r->day] = (float) $r->total;
        return ['labels' => array_keys($days), 'series' => array_values($days)];
    }

    /**
     * bookingsByStatus grouped by booking.status
     */
    public function bookingsByStatus(?int $doctorId = null, $from = null, $to = null): Collection
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        $rows = Booking::select('status', DB::raw('COUNT(*) as cnt'))
            ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->groupBy('status')->get();

        return $rows->pluck('cnt', 'status');
    }

    /**
     * paymentsByStatus reads from booking.payment_status
     */
    public function paymentsByStatus(?int $doctorId = null, $from = null, $to = null): Collection
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        $rows = Booking::select('payment_status', DB::raw('COUNT(*) as cnt'))
            ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->groupBy('payment_status')->get();

        return $rows->pluck('cnt', 'payment_status');
    }

    /**
     * topDoctorsByEarnings - sums doctor_amount (fallback total) on paid bookings
     */
    public function topDoctorsByEarnings(int $limit = 10, $from = null, $to = null): Collection
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        $rows = Booking::select('doctor_id', DB::raw('COALESCE(SUM(COALESCE(doctor_amount, total)),0) as total'))
            ->where('payment_status', 'Paid')
            ->whereBetween(DB::raw('DATE(payment_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy('doctor_id')->orderByDesc('total')->limit($limit)->get();

        return $rows;
    }

    /**
     * averageRate (rate stored on bookings)
     */
    public function averageRate(?int $doctorId = null, $from = null, $to = null): ?float
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);

        $q = Booking::query()
            ->whereNotNull('rate')
            ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId));

        $avg = $q->avg('rate');
        return is_null($avg) ? null : round((float) $avg, 2);
    }

    /**
     * uniquePatientsCount
     */
    public function uniquePatientsCount(?int $doctorId = null, $from = null, $to = null): int
    {
        [$fromDate, $toDate] = $this->normalizeDates($from, $to);
        return (int) Booking::query()
            ->whereBetween('booking_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->distinct('patient_id')->count('patient_id');
    }
}
