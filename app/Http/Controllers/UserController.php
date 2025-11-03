<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Files\FileController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;


use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        //$this->middleware('auth:sanctum')->except('index', 'show');
    }

    public function index()
    {

        return $this->success_message(['data'=>UserResource::collection( User::paginate(5))],
            'users retrieved successfully',200);
    }


    public static function store(StoreUserRequest $request)
    {


        $image= FileController::storeFile($request->file('image'),'images/users');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number'=>$request->phone_number,
            'image' => $image,
            'role_id'=>$request->role_id

        ]);

        return $user;
    }


    public function show(User $user)
    {

        return
            $this->success_message(new UserResource($user),'user found',200);


    }


    static function FindByEmail($email)
    {
        return User::where('email', $email)->first();
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $userData = $request->only('name', 'email');


        $user->image = FileController::updateFile($request->file('image'), $user->image,'images/users');
        $user->save();



        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return response()->json([new UserResource($user), 'User updated successfully'], 200);
    }

    public function destroy( User $user)
    {

//        $this->authorize('delete', $user);


        $user->delete();
        FileController::deleteFile($user->image,'images/users');
        PersonalAccessToken::where('tokenable_id', $user->id)->delete();//to delete all the tokens for the user
        return response()->json(['data'=>null,'message'=>'user deleted successfully'], 200);
    }
}
