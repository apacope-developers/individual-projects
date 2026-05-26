<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CleanPharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'district',
        'province',
        'latitude',
        'longitude',
        'license_number',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:11'
    ];

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
            ->orWhere('address', 'LIKE', "%{$term}%")
            ->orWhere('district', 'LIKE', "%{$term}%");
    }

    public function getFullAddressAttribute(): string
    {
        $address = $this->address ?? '';
        $district = $this->district ? ", {$this->district}" : '';
        $province = $this->province ? ", {$this->province}" : '';
        
        return trim($address . $district . $province, ', ');
    }
}
