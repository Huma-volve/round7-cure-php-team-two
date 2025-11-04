<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Requests\user\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeUser;
use App\Messages;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    public function register(StoreUserRequest $request)
    {
        $user = UserController::store($request);
        $user->assignRole('patient');
        $token = $user->createToken($request->name);


        return response()->json(['data'=>new UserResource($user),'token'=>$token->plainTextToken,'message'=>'registered successfully'],201);
    }


    public function login(LoginUserRequest $request): JsonResponse
    {



        $user = UserController::FindByEmail($request->email);


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
