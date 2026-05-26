<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_description',
        'related_item_type',
        'related_item_id',
        'search_query',
        'metadata',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Static methods for logging activities
    public static function logMedicineSearch($userId, $searchQuery, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => 'medicine_search',
            'activity_description' => "Searched for medicine: {$searchQuery}",
            'search_query' => $searchQuery,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    public static function logMedicinePurchase($userId, $orderId, $medicineName, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => 'medicine_purchase',
            'activity_description' => "Purchased medicine: {$medicineName}",
            'related_item_type' => 'order',
            'related_item_id' => $orderId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    public static function logInsuranceAdded($userId, $insuranceName, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => 'insurance_added',
            'activity_description' => "Added insurance: {$insuranceName}",
            'related_item_type' => 'insurance',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    public static function logInsuranceRemoved($userId, $insuranceName, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => 'insurance_removed',
            'activity_description' => "Removed insurance: {$insuranceName}",
            'related_item_type' => 'insurance',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    public static function logLogin($userId, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => 'login',
            'activity_description' => 'User logged in',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }

    public static function logLogout($userId, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => 'logout',
            'activity_description' => 'User logged out',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }
}
