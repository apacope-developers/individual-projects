<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInsurance extends Model
{
    protected $fillable = [
        'user_id',
        'insurance_id',
        'policy_number',
        'member_id',
        'expiry_date',
        'is_primary',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_primary' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }
}
