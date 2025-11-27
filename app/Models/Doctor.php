<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Doctor extends Model
{
    use SoftDeletes, HasRoles, HasFactory,Notifiable;

    protected $fillable = [
        'user_id',
        'specialty_id',
        'license_number',
        'session_price',
        'available_slots',
    ];
    protected $casts=[
        'available_slots'=>'array'
    ];
    protected $appends = ['average_rating', 'reviews_count'];

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    public function getReviewsCountAttribute()
    {
        return (int) $this->reviews()->count();
    }

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
        return $this->hasMany(Chat::class, 'doctor_id');
    }

    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable', 'favoritable_id', 'user_id')->withTimestamps();
    }
    public function scopeNearby(Builder $query, float $lat, float $lng, int $radiusKm = 10)
    {
        // Haversine using users.latitude/users.longitude
        $haversine = "(6371 * acos( cos( radians(?) ) * cos( radians(users.latitude) ) * cos( radians(users.longitude) - radians(?) ) + sin( radians(?) ) * sin( radians(users.latitude) ) ))";

        return $query->join('users', 'doctors.user_id', '=', 'users.id')
            ->selectRaw("doctors.* , users.latitude as clinic_lat, users.longitude as clinic_lng, users.phone_number as clinic_phone, users.profile_photo as clinic_avatar, $haversine AS distance", [$lat, $lng, $lat])
            ->whereNotNull('users.latitude')
            ->whereNotNull('users.longitude')
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

}
