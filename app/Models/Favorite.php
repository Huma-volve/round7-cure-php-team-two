<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Favorite extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_id',
        'favoritable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //polymorphic relationship(doctor or hospital)
    public function favoritable()
    {
        return $this->morphTo();
    }
}
