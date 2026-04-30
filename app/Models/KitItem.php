<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'is_checked',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    // Category labels
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'bandages'   => 'Bandages & Dressings',
            'medications' => 'Medications',
            'tools'       => 'Tools & Equipment',
            default       => 'Other Essentials',
        };
    }

    // Category icons
    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'bandages'   => 'fa-bandage',
            'medications' => 'fa-pills',
            'tools'       => 'fa-screwdriver-wrench',
            default       => 'fa-box',
        };
    }

    // Completion percentage for a category
    public static function completionPercentage(string $category): float
    {
        $total = self::where('category', $category)->count();
        if ($total === 0) return 0;
        $checked = self::where('category', $category)->where('is_checked', true)->count();
        return round(($checked / $total) * 100);
    }
}