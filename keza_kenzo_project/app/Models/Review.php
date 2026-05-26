<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'order_id',
        'medicine_id',
        'pharmacy_id',
        'rating',
        'title',
        'comment',
        'pros',
        'cons',
        'service_rating',
        'price_rating',
        'quality_rating',
        'delivery_rating',
        'would_recommend',
        'is_verified',
        'is_featured',
        'helpful_count',
        'not_helpful_count',
        'admin_response',
        'admin_response_date'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'service_rating' => 'integer',
        'price_rating' => 'integer',
        'quality_rating' => 'integer',
        'delivery_rating' => 'integer',
        'would_recommend' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
        'admin_response_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    public function getStarRatingAttribute()
    {
        $rating = round($this->rating);
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '⭐';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    public function getStarHtmlAttribute()
    {
        $rating = round($this->rating, 1);
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $stars .= '<i class="fas fa-star text-yellow-400"></i>';
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '<i class="fas fa-star-half-alt text-yellow-400"></i>';
            } else {
                $stars .= '<i class="far fa-star text-gray-300"></i>';
            }
        }
        return $stars;
    }

    public function getRatingColorAttribute()
    {
        if ($this->rating >= 4.5) return 'text-green-600';
        if ($this->rating >= 3.5) return 'text-blue-600';
        if ($this->rating >= 2.5) return 'text-yellow-600';
        if ($this->rating >= 1.5) return 'text-orange-600';
        return 'text-red-600';
    }

    public function getRatingBadgeAttribute()
    {
        $colors = [
            'text-green-600' => 'bg-green-100',
            'text-blue-600' => 'bg-blue-100',
            'text-yellow-600' => 'bg-yellow-100',
            'text-orange-600' => 'bg-orange-100',
            'text-red-600' => 'bg-red-100'
        ];

        $color = $this->rating_color;
        $bgColor = $colors[$color] ?? 'bg-gray-100';

        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$bgColor} {$color}'>{$this->formatted_rating} ⭐</span>";
    }

    public function getHelpfulScoreAttribute()
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        if ($total === 0) return 0;
        
        return round(($this->helpful_count / $total) * 100, 1);
    }

    public function getIsPositiveAttribute()
    {
        return $this->rating >= 3.0;
    }

    public function getRelativeTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByMedicine($query, $medicineId)
    {
        return $query->where('medicine_id', $medicineId);
    }

    public function scopeByPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithRatings($query, $minRating = null, $maxRating = null)
    {
        if ($minRating !== null) {
            $query->where('rating', '>=', $minRating);
        }
        
        if ($maxRating !== null) {
            $query->where('rating', '<=', $maxRating);
        }
        
        return $query;
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeMostHelpful($query)
    {
        return $query->orderByRaw('(helpful_count - not_helpful_count) DESC');
    }

    public function scopeHighestRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function scopeLowestRated($query)
    {
        return $query->orderBy('rating', 'asc');
    }
}
