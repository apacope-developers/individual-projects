<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CleanMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'manufacturer',
        'strength',
        'dosage_form',
        'price',
        'image',
        'requires_prescription',
        'is_active'
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/medicines/' . $this->image);
        }
        
        return 'https://via.placeholder.com/100x100/0ea5e9/ffffff?text=' . urlencode(substr($this->name, 0, 3));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%")
            ->orWhere('manufacturer', 'LIKE', "%{$term}%");
    }
}
