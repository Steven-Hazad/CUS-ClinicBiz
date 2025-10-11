<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'specialization', 'schedule', 'status'];

    protected $casts = [
        'schedule' => 'array', // JSON schedule as array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
     public function availabilities()
         {
             return $this->hasMany(Availability::class);
         }
}