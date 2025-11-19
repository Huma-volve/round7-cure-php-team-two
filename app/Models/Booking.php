<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Booking extends Model
{
    use SoftDeletes;

    protected $casts = [
        'booking_date' => 'date',
        'payment_time'=>'datetime'
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'booking_date',
        'booking_time',
        'status',
        'payment_method',
        'payment_status',
        'stripe_payment_intent',
        'stripe_session_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

}
