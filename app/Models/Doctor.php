<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name', 
        'email',
        'phone',
        'whatsapp',
        'gender',
        'date_of_birth',
        'national_id',
        'medical_license_number',
        'specialty',
        'qualifications',
        'hospital_clinic',
        'province',
        'district',
        'address',
        'latitude',
        'longitude',
        'status',
        'is_available',
        'working_hours',
        'consultation_fee',
        'bio',
        'profile_photo',
        'languages_spoken',
        'years_of_experience',
        'license_expiry',
        'emergency_contact_name',
        'emergency_contact_phone',
        'last_active_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'license_expiry' => 'date',
        'is_available' => 'boolean',
        'working_hours' => 'array',
        'languages_spoken' => 'array',
        'last_active_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_available', true);
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', 'like', "%{$specialty}%");
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getLocationAttribute()
    {
        return "{$this->district}, {$this->province}";
    }

    // Methods
    public function isCurrentlyAvailable()
    {
        if (!$this->is_available || $this->status !== 'active') {
            return false;
        }

        $now = now();
        $dayOfWeek = strtolower($now->format('l'));
        $currentTime = $now->format('H:i');

        if (!isset($this->working_hours[$dayOfWeek])) {
            return false;
        }

        $hours = $this->working_hours[$dayOfWeek];
        return $currentTime >= $hours['start'] && $currentTime <= $hours['end'];
    }

    public function updateLastActive()
    {
        $this->update(['last_active_at' => now()]);
    }

    public function getContactMethods()
    {
        $methods = ['phone' => $this->phone];
        
        if ($this->whatsapp) {
            $methods['whatsapp'] = $this->whatsapp;
        }
        
        if ($this->email) {
            $methods['email'] = $this->email;
        }
        
        return $methods;
    }

    // Rwanda-specific methods
    public static function getProvinces()
    {
        return [
            'Kigali' => 'Kigali Province',
            'Northern' => 'Northern Province', 
            'Southern' => 'Southern Province',
            'Eastern' => 'Eastern Province',
            'Western' => 'Western Province'
        ];
    }

    public static function getSpecialties()
    {
        return [
            'General Practice',
            'Pediatrics',
            'Obstetrics & Gynecology',
            'Surgery',
            'Internal Medicine',
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Psychiatry',
            'Dermatology',
            'Ophthalmology',
            'ENT (Ear, Nose, Throat)',
            'Emergency Medicine',
            'Anesthesiology',
            'Radiology',
            'Pathology',
            'Oncology',
            'Nephrology',
            'Gastroenterology',
            'Endocrinology'
        ];
    }

    public static function getLanguages()
    {
        return [
            'Kinyarwanda',
            'English', 
            'French',
            'Swahili'
        ];
    }
}
