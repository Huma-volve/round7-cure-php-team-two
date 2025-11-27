<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionFeedback extends Model
{
    protected $table = 'session_feedbacks';
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'doctor_id',
        'patient_id',
        'overall_experience',
        'notes',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
