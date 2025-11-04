<?php

namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['bail','required', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'latitude' => 'bail|required|numeric|between:-90,90',
            'longitude' => 'bail|required|numeric|between:-180,180',
            'image'=>'bail|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_number'=>'bail|required',

        ];
    }

}
