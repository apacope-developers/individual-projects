<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'work_date',
        'medicines_restocked',
        'orders_fulfilled',
        'customers_served',
        'issues_reported',
        'notes',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
