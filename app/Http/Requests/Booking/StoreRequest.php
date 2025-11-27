<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueBookingDateTime;

class StoreRequest extends FormRequest
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
            'doctor_id' => 'required|exists:doctors,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => ['required','date_format:H:i:s',new UniqueBookingDateTime()],
            'payment_method' => 'required|in:PayPal,Stripe,Cash',
        ];
    }
}
