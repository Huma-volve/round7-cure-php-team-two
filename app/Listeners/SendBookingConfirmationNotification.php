<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingConfirmationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
     public function handle(BookingConfirmed $event)
    {
        $booking = $event->booking;
        $user = $booking->user;

        Notification::create([
            'to_user_id' => $user->id,
            'message' => 'تم تأكيد الحجز رقم ' . $booking->id,
            'type_id' => $booking->id,
            'type_model' => get_class($booking),
            'is_read' => false,
        ]);
    }
}
