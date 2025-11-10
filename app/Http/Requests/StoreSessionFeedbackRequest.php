<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionFeedbackRequest extends FormRequest
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
            'booking_id' => 'required|exists:bookings,id',
            'doctor_id' => 'required|exists:doctors,id',
            'overall_experience' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
