<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['medicine_id', 'pharmacy_id', 'stock', 'price', 'batch_number', 'manufacture_date', 'expiry_date', 'storage_location', 'reorder_level', 'max_stock_level'];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}