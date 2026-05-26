<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicinePrice extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'medicine_id',
        'pharmacy_id',
        'unit_price',
        'previous_price',
        'price_change_date',
        'price_change_type', // 'increase', 'decrease', 'stable'
        'price_change_percentage',
        'is_promotional',
        'promotion_start_date',
        'promotion_end_date',
        'promotion_price',
        'currency',
        'notes'
    ];

    protected $casts = [
        'price_change_date' => 'datetime',
        'promotion_start_date' => 'datetime',
        'promotion_end_date' => 'datetime',
        'is_promotional' => 'boolean',
        'unit_price' => 'decimal:2',
        'previous_price' => 'decimal:2',
        'promotion_price' => 'decimal:2',
        'price_change_percentage' => 'decimal:2'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'RWF ' . number_format($this->unit_price, 0);
    }

    public function getFormattedPreviousPriceAttribute()
    {
        return 'RWF ' . number_format($this->previous_price, 0);
    }

    public function getPriceChangeIconAttribute()
    {
        if ($this->price_change_type === 'increase') {
            return '📈';
        } elseif ($this->price_change_type === 'decrease') {
            return '📉';
        }
        return '➡️';
    }

    public function getPriceChangeColorAttribute()
    {
        return [
            'increase' => 'text-red-600',
            'decrease' => 'text-green-600',
            'stable' => 'text-gray-600'
        ][$this->price_change_type] ?? 'text-gray-600';
    }

    public function getIsOnPromotionAttribute()
    {
        if (!$this->is_promotional) {
            return false;
        }

        $now = now();
        return $this->promotion_start_date && $this->promotion_end_date &&
               $now->between($this->promotion_start_date, $this->promotion_end_date);
    }

    public function getPromotionSavingsAttribute()
    {
        if (!$this->isOnPromotion) {
            return 0;
        }

        return $this->unit_price - $this->promotion_price;
    }

    public function getFormattedPromotionSavingsAttribute()
    {
        return 'RWF ' . number_format($this->promotion_savings, 0);
    }

    public function scopeCurrent($query)
    {
        return $query->where(function ($q) {
            $q->where('is_promotional', false)
              ->orWhere(function ($subQuery) {
                  $subQuery->where('is_promotional', true)
                           ->where('promotion_start_date', '<=', now())
                           ->where('promotion_end_date', '>=', now());
              });
        });
    }

    public function scopeByMedicine($query, $medicineId)
    {
        return $query->where('medicine_id', $medicineId);
    }

    public function scopeByPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }

    public function scopeWithPriceHistory($query, $days = 30)
    {
        return $query->where('price_change_date', '>=', now()->subDays($days));
    }

    public function scopeLowestPrice($query, $medicineId)
    {
        return $query->where('medicine_id', $medicineId)
                    ->orderBy('unit_price', 'asc');
    }

    public function scopeHighestPrice($query, $medicineId)
    {
        return $query->where('medicine_id', $medicineId)
                    ->orderBy('unit_price', 'desc');
    }
}
