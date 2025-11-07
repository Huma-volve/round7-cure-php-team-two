<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // هنا تتحقق إن المستخدم مشترك في الشات ده فعلاً
    return \App\Models\ChatParticipant::where('chat_id', $chatId)
        ->where('user_id', $user->id)
        ->exists();
});