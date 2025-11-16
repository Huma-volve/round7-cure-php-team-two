<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_id', 'label', 'type', 'last_message_id',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants')
            ->withPivot([
                'joined_at', 'role'
            ]);
    }

    public function recipients()
    {
        return $this->hasManyThrough(
            Recipient::class, 
            Message::class, 
            'conversation_id', 
            'message_id', 
            'id', 
            'id'
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id', 'id')
            ->whereNull('deleted_at')
            ->withDefault([
                'body' => 'Message deleted'
            ]);
    }
}
