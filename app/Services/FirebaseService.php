<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


class FirebaseService
{
        protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.projects.app.credentials'));

        $this->messaging = $factory->createMessaging();
    }

     public function sendPushNotification(string $title, string $body, string $token, array $data = [])
    {
       if (empty($token)) {
        return;
         }

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(['title' => $title, 'body' => $body])
            ->withData($data);

        $this->messaging->send($message);
    }

}
