<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = ['name', 'location', 'province', 'phone'];

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'availabilities');
    }
}