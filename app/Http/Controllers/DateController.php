<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DateController extends Controller
{
    public static function gettDiffInMinutes($start, $end)
    {
        $startTime = Carbon::parse($start);
        $endTime   = Carbon::parse($end);

        $totalMinutes = $startTime->diffInMinutes($endTime);
        return $totalMinutes;
    }
}
