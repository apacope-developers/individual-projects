<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
protected $fillable = [
    'name',
    'email',
    'ticket_type',
    'event_id',
    'phone',
    'payment_status',
    'user_id',
];

    public function event()
{
    return $this->belongsTo(\App\Models\Event::class);
}
}