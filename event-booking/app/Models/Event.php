<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
 protected $fillable = [
    'title',
    'description',
    'date',
    'location',
    'max_attendees',
    'image' // 👈 ADD THIS
];

   public function attendees()
{
    return $this->hasMany(\App\Models\Booking::class);
}

public function bookings()
{
    return $this->hasMany(\App\Models\Booking::class);
}
}