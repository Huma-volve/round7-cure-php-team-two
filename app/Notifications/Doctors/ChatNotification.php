<?php

namespace App\Notifications\Doctors;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;

class ChatNotification extends Notification
{
    use Queueable;
    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($message)
    {
        //
        $this->message = $message;
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
            'chat_id' => $this->message->chat_id,
            'sender_id' => $this->message->sender->full_name,
            'message_preview' => Str::limit($this->message->content, 50),
            'type' => 'chat_message',
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => 'ChatNotification',
            'data' => $this->toArray($notifiable),
            'read_at' => null,
        ]);
    }
}
