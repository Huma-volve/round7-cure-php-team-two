<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Booking;
use Carbon\Carbon;

class UniqueBookingDateTime implements ValidationRule
{
    protected ?int $ignoreBookingId;

    public function __construct(?int $ignoreBookingId = null)
    {
        $this->ignoreBookingId = $ignoreBookingId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Build full datetime from request fields (assumes booking_date + booking_time in request)
        $date = request('booking_date');
        $time = request('booking_time');

        $fullDateTime = Carbon::parse($date . ' ' . $time)->format('Y-m-d H:i:s');

        $query = Booking::whereRaw("CONCAT(booking_date, ' ', booking_time) = ?", [$fullDateTime]);

        if ($this->ignoreBookingId) {
            $query->where('id', '!=', $this->ignoreBookingId);
        }

        if ($query->exists()) {
            $fail('This booking date and time is already taken.');
        }
    }
}
