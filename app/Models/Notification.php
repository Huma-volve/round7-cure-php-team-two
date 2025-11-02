<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'to_user_id',
        'message',
        'type_id',
        'type_model',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    //polymorphic relationship
    public function type()
    {
        return $this->morphTo(__FUNCTION__, 'type_model', 'type_id');
    }

}

