<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query' => 'required|string|min:2|max:255',
            'location' => 'nullable|string|min:2|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'query.required' => 'Please enter a search query',
            'query.string' => 'Query must be a valid text',
            'query.min' => 'Query is too short',
            'query.max' => 'Query is too long',
            'location.string' => 'Location must be a valid text',
            'location.min' => 'Location is too short',
            'location.max' => 'Location is too long',
        ];
    }
    }

