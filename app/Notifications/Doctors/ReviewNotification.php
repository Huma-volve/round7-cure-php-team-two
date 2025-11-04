<?php

namespace App\Notifications\Doctors;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;

class ReviewNotification extends Notification
{
    use Queueable;
    private $review;
    /**
     * Create a new notification instance.
     */
    public function __construct($review)
    {
        //
        $this->review = $review;
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
            'patient' => $this->review->patient->user->full_name,
            'rating' => $this->review->rating,
            'comment' => Str::limit($this->review->comment, 50),
            'type' => 'review',
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => 'ReviewNotification',
            'data' => $this->toArray($notifiable),
            'read_at' => null,
        ]);
    }
}
