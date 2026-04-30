<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'type',
        'icon',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Scope for non-default contacts (user-added)
    public function scopeCustom($query)
    {
        return $query->where('is_default', false);
    }

    // Type color mapping for views
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'emergency' => 'bg-red-600/20 text-red-400',
            'medical'   => 'bg-blue-600/20 text-blue-400',
            default     => 'bg-teal-600/20 text-teal-400',
        };
    }
}