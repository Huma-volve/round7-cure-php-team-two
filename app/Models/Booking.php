<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Str;
class Booking extends Model
{
    use SoftDeletes;

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'string',
        'payment_time' => 'datetime',
        'doctor_amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'booking_date',
        'booking_time',
        'status',
        'payment_method',
        'payment_status',
        'payment_time',
        'doctor_amount',
        'rate',
        'total',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function user()
    {
        return $this->hasOneThrough(User::class, Patient::class, 'id', 'id', 'patient_id', 'user_id');
    }

    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    public function isPaid(): bool
    {
        return strtolower($this->payment_status) === 'paid';
    }

    public function markAsPaid($amount = null, $method = null, $time = null)
    {
        $this->payment_status = 'Paid';
        if ($method)
            $this->payment_method = $method;
        if ($amount !== null)
            $this->total = $amount;
        $this->doctor_amount = $this->doctor_amount ?: ($this->total * 0.7); // default 70%
        $this->payment_time = $time ? Carbon::parse($time) : Carbon::now();
        $this->save();
    }

}
