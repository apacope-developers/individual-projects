<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'medicine_id',
        'stock_quantity',
        'reorder_level',
        'max_stock',
        'selling_price',
        'batch_number',
        'expiry_date',
        'storage_location',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
        'reorder_level' => 'integer',
        'max_stock' => 'integer',
        'selling_price' => 'decimal:2',
        'expiry_date' => 'date'
    ];

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(CleanPharmacy::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(CleanMedicine::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'reorder_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', 0);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity === 0;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && $this->expiry_date->lessThanOrEqualTo(now()->addDays($days));
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }
        
        if ($this->isLowStock()) {
            return 'low_stock';
        }
        
        if ($this->isExpiringSoon()) {
            return 'expiring_soon';
        }
        
        return 'in_stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'red',
            'low_stock' => 'yellow',
            'expiring_soon' => 'orange',
            default => 'green'
        };
    }
}
