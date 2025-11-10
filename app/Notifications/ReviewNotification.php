<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewNotification extends Notification
{
    use Queueable;
    protected $review;


    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        //
        $this->review=$review;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
      public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "You have a new review from {$this->review->user->name}",
            'review_id' => $this->review->id,
            'booking_id' => $this->review->booking_id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
        ];
    }
}
