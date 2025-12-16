<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
      public function index(){
        // dd(Auth::user());
        return view('Dashboard.layouts.dashboard');
    }
}
