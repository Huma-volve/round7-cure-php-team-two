<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Specialty extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
