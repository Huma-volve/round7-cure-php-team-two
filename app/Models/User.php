<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\Messagesent;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'profile_photo',
        'latitude',
        'longitude',
        'last_login_at',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class);
    }
    public function favoriteDoctors()
    {
        return $this->morphedByMany(
            Doctor::class,
            'favoritable',
            'favorites',
            'user_id',
            'favoritable_id'
        )->withTimestamps();
    }

    public function isFavorite(Doctor $doctor): bool
    {
        return $this->favorites()
            ->where('favoritable_type', Doctor::class)
            ->where('favoritable_id', $doctor->id)
            ->exists();
    }
    public function chats()
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

      public function routeNotificationForOneSignal() : array{
        return ['tags'=>['key'=>'userId','relation'=>'=', 'value'=>(string)($this->id)]];
    }

     public function sendNewMessageNotification(array $data) : void {
        $this->notify(new Messagesent($data));
    }

}
