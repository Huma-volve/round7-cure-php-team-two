<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{

     public function index($id = null)
    {
        $user = Auth::user();
        $friends = User::where('id', '<>', $user->id)
        ->orderBy('name')
        ->paginate();
         

        return view('massenger', [
            'friends' => $friends,
        ]);
    }
}
