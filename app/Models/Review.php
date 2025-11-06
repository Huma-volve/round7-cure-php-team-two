<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{

    use SoftDeletes, HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'rating',
        'comment',
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
        return $this->patient->user();
    }
}
