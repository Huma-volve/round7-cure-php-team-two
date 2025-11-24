<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public static function store_user_image($request,$user)
    {
        if ($request->hasFile('profile_image') && $request->input('remove_image') == 0) {
            FileController::storeFile($user->profile_photo, 'images/users');

        }


    }
    public static function update_user_image($request,$user)
    {

        if (!$request->hasFile('profile_image') && $request->input('remove_image') == 1) {
            FileController::deleteFile($user->profile_photo, 'images/users');
            return  null;
        }

        if ($request->hasFile('profile_image')) {
            $image = FileController::updateFile($request->file('profile_image'), $user->profile_photo, 'images/users');
            return  $image;
        }
    }
}
