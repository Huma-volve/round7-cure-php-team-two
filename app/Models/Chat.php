<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'is_private',
        'created_by',
    ];


    public function participants()
    {
        return $this->hasMany(Chat_Participant::class,'chat_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

       public function lastMessage()
    {
        return $this->hasOne(Message::class, 'chat_id')->latest('updated_at');
    }

    public function scopeHasParticipant($query, int $userId){
        return $query->whereHas('participants', function($q) use ($userId){
            $q->where('user_id',$userId);
        });
    }
}
