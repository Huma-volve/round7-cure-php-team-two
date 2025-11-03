<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

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

    //polymorphic relationship(booking or message or payment)
    public function notifiable()
    {
        return $this->morphTo();
    }
}
