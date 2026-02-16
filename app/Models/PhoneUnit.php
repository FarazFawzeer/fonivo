<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneUnit extends Model
{
    protected $fillable = [
        'product_id','imei1','imei2','condition','battery_health',
        'faults','included_items','warranty_days',
        'purchase_cost','expected_sell_price','status'
    ];

    protected $casts = [
        'purchase_cost' => 'decimal:2',
        'expected_sell_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItem()
    {
        return $this->hasOne(PurchaseItem::class);
    }

    public function salesItem()
    {
        return $this->hasOne(SalesItem::class);
    }
}
