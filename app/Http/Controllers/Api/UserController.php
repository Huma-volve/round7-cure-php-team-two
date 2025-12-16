<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Files\FileController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
// use Illuminate\Routing\Controller;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('show', 'index');
    }

    public function index()
    {
        $user = UserResource::collection(User::paginate(5));
        return response()->json([
            'data' => $user,
            'message' => 'users retrieved successfully'
        ], 200);
    }


    public static function store(StoreUserRequest $request)
    {


        if ($request->hasFile('image')) {
            $image = FileController::storeFile($request->file('image'), 'images/users');
        } else {
            $image = 'user.png';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'latitude',
            $request->latitude,
            'longitude',
            $request->longitude,
            'phone_number' => $request->phone_number,
            'profile_photo' => $image,


        ]);

        return $user;
    }
    public function getAuthenticatedUser()
    {
        $user = auth()->user();
        return response()->json([
            'data' => new UserResource($user),
            'message' => 'user retrieved successfully'
        ], 200);
    }

    public function show(User $user)
    {

        return
            response()->json(['data' => new UserResource($user), 'message' => 'user found'], 200);
    }


    static function FindByEmail($email)
    {
        return User::where('email', $email)->first();
    }


    public  function update(UpdateUserRequest $request,$user=null)
    {
        if($user==null){$user = auth()->user();}


        $this->authorize('update',$user);

        $userData = $request->only('name', 'email');


        $user->profile_photo = FileController::updateFile($request->file('image'), $user->profile_photo, 'images/users');
        $user->save();



        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return response()->json([new UserResource($user), 'User updated successfully'], 200);
    }


    public function destroy($user=null)

    {
        if($user==null){$user = auth()->user();}
        $this->authorize('delete', $user);
        $user->forceDelete();
        FileController::deleteFile($user->image, 'images/users');
        PersonalAccessToken::where('tokenable_id', $user->id)->delete(); //to delete all the tokens for the user
        return response()->json(['data' => null, 'message' => 'user deleted successfully'], 200);
    }
    public static function updatePassword(UpdatePasswordRequest $request)
    {
         $user=User::where('email','=',$request->email)->first();

         $user->password=Hash::make($request->password);
         $user->save();
        return $user;
    }
}
