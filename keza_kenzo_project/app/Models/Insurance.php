<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_phone',
        'contact_email',
        'description',
        'is_active',
        'coverage_percentage',
        'logo_url'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'coverage_percentage' => 'decimal:2'
    ];

    public function userInsurances()
    {
        return $this->hasMany(UserInsurance::class);
    }
}
