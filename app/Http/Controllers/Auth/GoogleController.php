<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->token);

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => encrypt(random_bytes(16)),


            ]
        );

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'data' => $user,
            'token' => $token,

        ], 200);
    }
}
