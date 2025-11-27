<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentNotification
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
    public function handle(PaymentCompleted $event)
    {
        $payment = $event->payment;
        $user = $payment->user;
        Notification::create([
            'to_user_id' => $user->id,
            'message' => 'تم استلام الدفع بنجاح!',
            'type_id' => $payment->id,
            'type_model' => get_class($payment),
            'is_read' => false,
        ]);
    }
}
