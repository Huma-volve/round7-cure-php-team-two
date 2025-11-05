<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'birth_date',
        'medical_history',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'patient_id');
    }

    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }
}
