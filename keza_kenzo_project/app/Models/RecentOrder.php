<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'medicine_name',
        'pharmacy_name',
        'pharmacy_location',
        'quantity',
        'total_amount',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the recent order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order that this recent order references.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get formatted total amount.
     */
    public function getFormattedAmountAttribute()
    {
        return 'RWF ' . number_format($this->total_amount, 0);
    }

    /**
     * Get time ago in human readable format.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope to get recent orders for a user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->orderBy('created_at', 'desc');
    }

    /**
     * Scope to limit to recent records.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->limit($limit);
    }
}
