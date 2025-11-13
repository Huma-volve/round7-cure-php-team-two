<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users','email')->ignore(Auth::user()->id),
            ],

            'password' => 'nullable|string|min:8|confirmed',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }


}
