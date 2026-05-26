<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userInsurances()
    {
        return $this->hasMany(UserInsurance::class);
    }

    public function primaryInsurance()
    {
        return $this->hasOne(UserInsurance::class)->where('is_primary', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function recentOrders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc')->take(10);
    }

    public function userActivities()
    {
        return $this->hasMany(UserActivity::class)->orderBy('created_at', 'desc');
    }

    public function recentActivities()
    {
        return $this->hasMany(UserActivity::class)->orderBy('created_at', 'desc')->take(20);
    }

    public function recentSearches()
    {
        return $this->hasMany(UserActivity::class)
            ->where('activity_type', 'medicine_search')
            ->orderBy('created_at', 'desc')
            ->take(10);
    }

    public function recentPurchases()
    {
        return $this->hasMany(Order::class)
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->take(10);
    }
}
