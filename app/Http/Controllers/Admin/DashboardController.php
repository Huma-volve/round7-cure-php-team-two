<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Reports\AnalyticsService;
class DashboardController extends Controller
{
    //
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

}
