<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingStatusNotification
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
    public function handle(BookingStatusChanged $event)
    {
        $booking = $event->booking;
        $user = $booking->user;

        Notification::create([
            'to_user_id' => $user->id,
            'message' => 'تم تغيير حالة الحجز رقم ' . $booking->id . ' إلى ' . $booking->status,
            'type_id' => $booking->id,
            'type_model' => get_class($booking),
            'is_read' => false,
        ]);
    }
}
