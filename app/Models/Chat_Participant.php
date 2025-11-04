<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat_Participant extends Model
{

    protected $table='chat_participants';
    protected $fillable=['chat_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
