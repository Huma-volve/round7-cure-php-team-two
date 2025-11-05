<?php

namespace App\Notifications\Doctors;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookingNotification extends Notification
{
    use Queueable;

    private $booking;
    private $type;
    /**
     * Create a new notification instance.
     */
    public function __construct($booking, $type)
    {
        //
        $this->booking = $booking;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'booking_id' => $this->booking->id,
            'type' => $this->type,
            'message' => $this->getMessage(),
            'patient_name' => $this->booking->patient->user->full_name,
            'date_time' => $this->booking->date_time,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => get_class($this),
            'data' => [
                'booking_id' => $this->booking->id,
                'type' => $this->type,
                'message' => $this->getMessage(),
                'patient_name' => $this->booking->patient->user->full_name,
                'date_time' => $this->booking->date_time->toDateTimeString(),
            ],
            'read_at' => null,
            'created_at' => now()->toDateTimeString(),
        ]);
    }
    private function getMessage()
    {
        return match ($this->type) {
            'new_booking' => 'New booking has been done',
            'cancelled' => 'one booking has been cancelled',
            default => 'Booking status updated'
        };
    }
}
