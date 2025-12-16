<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Messenger.{id}', function($user, $id) {
    if ($user->id == $id) {
        return $user;
    }
});
Broadcast::channel('Chat', function($user) {
    return $user;
});
