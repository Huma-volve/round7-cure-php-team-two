<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'specialty_id',
        'license_number',
        'session_price',
        'available_slots',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
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
        return $this->hasMany(Chat::class);
    }

    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function favorites()
{
    return $this->morphMany(Favorite::class, 'favoritable');
}

}
