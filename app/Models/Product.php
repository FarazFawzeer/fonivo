<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id','name','brand','model','sku',
        'default_cost_price','default_sell_price','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_cost_price' => 'decimal:2',
        'default_sell_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Phones
    public function phoneUnits(): HasMany
    {
        return $this->hasMany(PhoneUnit::class);
    }

    // Accessories ledger
    public function stockLedgers(): HasMany
    {
        return $this->hasMany(StockLedger::class);
    }
}
