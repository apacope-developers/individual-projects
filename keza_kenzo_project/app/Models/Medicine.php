<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name', 'price', 'image', 'category', 'manufacturer', 'strength'];

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class, 'availabilities');
    }

    public function prices()
    {
        return $this->hasMany(MedicinePrice::class);
    }

    public function currentPrices()
    {
        return $this->hasMany(MedicinePrice::class)->current();
    }

    public function lowestPrice()
    {
        return $this->prices()->lowestPrice($this->id)->first();
    }

    public function highestPrice()
    {
        return $this->prices()->highestPrice($this->id)->first();
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/medicines/' . $this->image);
        }
        
        // Return a placeholder image if no image is set
        return 'https://via.placeholder.com/100x100/0ea5e9/ffffff?text=' . urlencode(substr($this->name, 0, 3));
    }
}