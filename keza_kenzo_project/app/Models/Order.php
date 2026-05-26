<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "order_number",
        "user_id", 
        "medicine_id",
        "pharmacy_id",
        "quantity",
        "unit_price",
        "total_amount",
        "status",
        "payment_status",
        "payment_method",
        "delivery_address",
        "notes"
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}