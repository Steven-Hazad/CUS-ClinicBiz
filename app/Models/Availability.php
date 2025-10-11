<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['doctor_id', 'start_time', 'end_time', 'available'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
