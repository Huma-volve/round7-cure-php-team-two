<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Chat;

class GetMessageRequest extends FormRequest
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
        $chatModel = get_class(new Chat());

        return [
            'chat_id' => "required|exists:{$chatModel},id",
            'page' => 'required|numeric',
            'page_size' => 'nullable|numeric',
        ];
    }
}
