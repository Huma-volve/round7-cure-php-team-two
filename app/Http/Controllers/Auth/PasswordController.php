<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
    public function sendResetCode(Request $request){
        return $this->success(OtpController::sendOTPCode($request),'otp sent');
    }
    public function resetPassword(UpdatePasswordRequest $request)
    {
          $user=UserController::updatePassword($request);
        return $this->success(new UserResource($user),'password updated successfully');
    }
}
