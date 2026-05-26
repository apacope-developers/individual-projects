<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'search_query',
        'search_filters',
        'results_count',
    ];

    protected $casts = [
        'search_filters' => 'array',
        'results_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the recent search.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get time ago in human readable format.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope to get recent searches for a user.
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

    /**
     * Scope to search by query.
     */
    public function scopeByQuery($query, $searchQuery)
    {
        return $query->where('search_query', 'like', "%{$searchQuery}%");
    }
}
