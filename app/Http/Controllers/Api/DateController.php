<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


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
public static function IsValid($date){
    return Carbon::parse($date)->isAfter(Carbon::now());
}
}
