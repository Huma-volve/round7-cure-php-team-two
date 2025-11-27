<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\admin\AdminController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class HelperController extends UserController
{



    public static function GetHelpers()
    {
        return Admin::where('status','=','0')->paginate(10);
    }

    public static function storeHelper(StoreUserRequest $request)
    {
        $user= UserController::store($request);
        $helper=$user->admin()->create(['status' => 0]);
        $user->assignRole('helper');
        return $helper;

    }
    public static function updateHelper(UpdateUserRequest $request,User $user)
    {
        $userController=new UserController();

        $user=$userController->update($request,$user);
        return $user;


    }
    public static function DeleteHelper(User $user){
        $userController=new UserController();
        $user->admin()->delete();

       return  $userController->destroy($user);

    }
    public static function GetHelper()
    {

    }
}
