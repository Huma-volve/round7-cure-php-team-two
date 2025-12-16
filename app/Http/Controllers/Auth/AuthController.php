<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserController;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    public function register(StoreUserRequest $request)
    {
        $user = UserController::store($request);
        $user->assignRole('patient');
        $token = $user->createToken($request->name);
        Patient::create(['user_id'=>$user->id]);


        return response()->json(['data'=>new UserResource($user),'token'=>$token->plainTextToken,'message'=>'registered successfully'],201);
    }


    public function login(LoginUserRequest $request)
    {



        $user = User::withTrashed()->where('email', $request->email)->first();




        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['data'=>null,'message'=>'wrong password'],401);
        }

        $token = $user->createToken($user->name);


        return response()->json(['user'=>new UserResource($user),
            'token'=>$token->plainTextToken,'message'=>'login successfully'],200);
    }


    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json(['data'=>null,'message'=>'logout successfully'],200);
    }

  


}
